<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Array_;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController as Admin;
use Illuminate\Support\Facades\Session;

class TeamController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function team(Request $request) {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Team';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['request'] = $request->session()->get('datas');

        if($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');

        $per_page = 10;

        $team = DB::table('team')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('team')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['team'] = $team;
        $data['count'] = $count;

        return view('admin.team.team', ['data' => $data]);
    }

    public function teamDelete()
    {
        $id = $_GET['id'];
        DB::table('team')->delete($id);
    }

    public function teamEdit(Request $request) {

        $data['user'] = $request->user();

        $data['team'] = DB::table('team')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Employee';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Team', $href);

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['request'] = $request->session()->get('datas');

        if($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.team.teamEdit', ['data' => $data]);
    }

    public function teamPostEdit(Request $request) {
        $data = $_POST;
        if(empty($request->name) || empty($request->position)) {
            $request->session()->put('error', 'Fill in all the fields');
        }
        else {

            $db_link = DB::table('team')->where('id', '=', $request->id)->first();
            $this_date = date('YmdHis');

            $image = $db_link->image;
            $gif = $db_link->gif;

            $uploadedFile = $request->file('image');
            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                }
                $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            }

            $uploadedFile = $request->file('gif');
            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->gif->getClientOriginalName());
                }
                $gif = '/uploadfiles/' . $this_date . $request->gif->getClientOriginalName();
            }

            DB::table('team')->where('id', $request->id)->update(
                [
                    'name' => $request->name,
                    'position' => $request->position,
                    'image' => $image,
                    'gif' => $gif,
                    'class' => $request->class ? $request->class : '',
                    'sort' => $request->sort ? $request->sort : 0,
                    'status' => $request->status,
                ]
            );


            $request->session()->put('success', 'Successfully updated!');

        }
        return redirect('/admin/team/edit/'.$request->id)->with( ['datas' => $data] );
    }

    public function teamAdd(Request $request) {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Employee';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Team', $href);

        $data['request'] = $request->session()->get('datas');

        if($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.team.teamAdd', ['data' => $data]);
    }

    public function teamPostAdd(Request $request) {
        $data = $_POST;
        if(empty($request->name) || empty($request->position)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/team/add/')->with( ['datas' => $data] );
        }
        else {
            $this_date = date('YmdHis');

            $image = '';
            $gif = '';

            $uploadedFile = $request->file('image');
            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                }
                $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            }

            $uploadedFile = $request->file('gif');

            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->gif->getClientOriginalName());
                }
                $gif = '/uploadfiles/' . $this_date . $request->gif->getClientOriginalName();
            }


            DB::table('team')->insert(
                [
                    'name' => $request->name,
                    'position' => $request->position,
                    'image' => $image,
                    'gif' => $gif,
                    'class' => $request->class ? $request->class : '',
                    'sort' => $request->sort ? $request->sort : 0,
                    'status' => $request->status,
                ]
            );

            $request->session()->put('success', 'Successfully added!');
            return redirect('/admin/team/')->with( ['datas' => $data] );

        }
    }
}
