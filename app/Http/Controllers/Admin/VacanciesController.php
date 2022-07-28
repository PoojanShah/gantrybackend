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

class VacanciesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vacancies(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Vacancies';
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

        $team = DB::table('vacancies')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('vacancies')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['vacancies'] = $team;
        $data['count'] = $count;

        return view('admin.vacancies.vacancies', ['data' => $data]);
    }

    public function vacanciesDelete()
    {
        $id = $_GET['id'];
        DB::table('vacancies')->delete($id);
    }

    public function vacanciesEdit(Request $request) {

        $data['user'] = $request->user();

        $data['vacancies'] = DB::table('vacancies')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Vacancy';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Vacancies', $href);

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
        return view('admin.vacancies.vacanciesEdit', ['data' => $data]);
    }

    public function vacanciesPostEdit(Request $request) {
        $data = $_POST;
        if(empty($request->title) || empty($request->level)) {
            $request->session()->put('error', 'Fill in all the fields');
        }
        else {

            DB::table('vacancies')->where('id', $request->id)->update(
                [
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'level' => $request->level,
                    'description' => $request->description ? $request->description : '',
                    'sort' => $request->sort ? $request->sort : 0,
                    'status' => $request->status,
                ]
            );


            $request->session()->put('success', 'Successfully updated!');

        }
        return redirect('/admin/vacancies/edit/'.$request->id)->with( ['datas' => $data] );
    }

    public function vacanciesAdd(Request $request) {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Vacancy';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Vacancies', $href);

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
        return view('admin.vacancies.vacanciesAdd', ['data' => $data]);
    }

    public function vacanciesPostAdd(Request $request) {
        $data = $_POST;
        if(empty($request->title) || empty($request->level)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/vacancies/add/')->with( ['datas' => $data] );
        }
        else {
            DB::table('vacancies')->insert(
                [
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'level' => $request->level,
                    'description' => $request->description ? $request->description : '',
                    'sort' => $request->sort ? $request->sort : 0,
                    'status' => $request->status,
                ]
            );

            $request->session()->put('success', 'Successfully added!');
            return redirect('/admin/vacancies/')->with( ['datas' => $data] );

        }
    }
}
