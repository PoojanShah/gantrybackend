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

class PagesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pages(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Pages';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

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

        $data['pages'] = DB::table('pages')->orderBy('id', 'asc')->get();

        return view('admin.pages.pages', ['data' => $data]);
    }

    public function pagesDelete()
    {
        $id = $_GET['id'];
        DB::table('pages')->delete($id);
    }

    public function pagesAdd(Request $request)
    {

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Add Page';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Pages', $href);

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
        return view('admin.pages.pagesAdd', ['data' => $data]);
    }

    public function pagesPostAdd(Request $request)
    {
        $data = $_POST;
        if (empty($request->title) || empty($request->link)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/pages/add/')->with(['datas' => $data]);
        } else {
            $db_link = DB::table('pages')->where('link', '=', $request->link)->count();
            if ($db_link > 0) {
                $request->session()->put('error_link', 'This KEY is already in use!');
                return redirect('/admin/pages/add/')->with(['datas' => $data]);
            } else {

                $this_date = date('YmdHis');

                $image = '';


                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
                }

                DB::table('pages')->insert(
                    [
                        'title' => $request->title,
                        'link' => $request->link,
                        'image' => $image,
                        'description' => $request->description ? $request->description : '',
                        'status' => $request->status,
                        'seo_title' => $request->seo_title,
                        'seo_description' => $request->seo_description,
                        'seo_keywords' => $request->seo_keywords,
                    ]
                );

                $request->session()->put('success', 'Successfully added!');
                return redirect('/admin/pages/')->with(['datas' => $data]);
            }
        }

    }


    public function pagesEdit(Request $request)
    {

        $data['user'] = $request->user();

        $data['page'] = DB::table('pages')->where('id', '=', $request->id)->first();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Edit Page - ' . $data['page']->title . ' ';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Pages', $href);

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
        return view('admin.pages.pagesEdit', ['data' => $data]);
    }

    public function pagesPostEdit(Request $request)
    {
        $data = $_POST;


        if (empty($request->title) || empty($request->link)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            $db_link = DB::table('pages')->where('link', '=', $request->link)->first();
            if ($db_link != NULL && $request->id != $db_link->id) {
                $request->session()->put('error_link', 'This KEY is already in use!');
            } else {

                $db_link = DB::table('pages')->where('id', '=', $request->id)->first();
                $this_date = date('YmdHis');

                $image = $db_link->image;

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
                }

                DB::table('pages')->where('id', $request->id)->update(
                    [
                        'title' => $request->title,
                        'link' => $request->link,
                        'image' => $image,
                        'description' => $request->description ? $request->description : '',
                        'status' => $request->status,
                        'seo_title' => $request->seo_title,
                        'seo_description' => $request->seo_description,
                        'seo_keywords' => $request->seo_keywords,
                    ]
                );


                $request->session()->put('success', 'Successfully updated!');
            }
        }
        return redirect('/admin/pages/edit/' . $request->id)->with(['datas' => $data]);
    }
}
