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

class LogofolioController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function logofolio(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Logofolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['request'] = $request->session()->get('datas');

        if ($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if ($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if ($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');

        $per_page = 10;

        $logofolio = DB::table('logofolio')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('logofolio')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['logofolio'] = $logofolio;
        $data['count'] = $count;

        return view('admin.logofolio.logofolio', ['data' => $data]);
    }

    public function logofolioDelete()
    {
        $id = $_GET['id'];
        DB::table('logofolio')->delete($id);
    }

    public function logofolioEdit(Request $request) {

        $data['user'] = $request->user();

        $data['logofolio'] = DB::table('logofolio')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Logo';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Logofolio', $href);

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
        return view('admin.logofolio.logofolioEdit', ['data' => $data]);
    }

    public function logofolioPostEdit(Request $request) {
        $data = $_POST;

        $db_link = DB::table('logofolio')->where('id', '=', $request->id)->first();
        $this_date = date('YmdHis');

        $image = $db_link->image;


        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
        }


        DB::table('logofolio')->where('id', $request->id)->update(
            [
                'image' => $image,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
            ]
        );


        $request->session()->put('success', 'Successfully updated!');

        return redirect('/admin/logofolio/edit/'.$request->id)->with( ['datas' => $data] );
    }

    public function logofolioAdd(Request $request) {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Logo';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Logofolio', $href);

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
        return view('admin.logofolio.logofolioAdd', ['data' => $data]);
    }

    public function logofolioPostAdd(Request $request) {
        $data = $_POST;

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

        DB::table('logofolio')->insert(
            [
                'image' => $image,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
            ]
        );

        $request->session()->put('success', 'Successfully added!');
        return redirect('/admin/logofolio/')->with( ['datas' => $data] );

    }
}
