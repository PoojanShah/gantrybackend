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

class BlogController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function blog(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Blog';
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

        $articles = DB::table('blog')->orderBy('date', 'desc')->paginate($per_page);

        $count = DB::table('blog')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['articles'] = $articles;
        $data['count'] = $count;

        return view('admin.blog.blog', ['data' => $data]);
    }

    public function blogDelete()
    {
        $id = $_GET['id'];
        DB::table('blog')->delete($id);
    }

    public function blogAdd(Request $request)
    {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Article';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Article', $href);

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
        return view('admin.blog.blogAdd', ['data' => $data]);
    }

    public function blogEdit(Request $request)
    {

        $data['user'] = $request->user();

        $data['article'] = DB::table('blog')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Article';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Blog', $href);

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
        return view('admin.blog.blogEdit', ['data' => $data]);
    }

    public function blogPostEdit(Request $request)
    {
        $data = $_POST;
        if (empty($request->title) || empty($request->slug)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {

            $db_link = DB::table('blog')->where('slug', '=', $request->slug)->first();

            if ($db_link != NULL && $request->id != $db_link->id) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
            } else {
                $db_link = DB::table('blog')->where('id', '=', $request->id)->first();
                $this_date = date('YmdHis');

                $image = $db_link->image;
                $inner_image = $db_link->inner_image;
                

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
                }

                $uploadedFile = $request->file('inner_image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->inner_image->getClientOriginalName());
                    }
                    $inner_image = '/uploadfiles/' . $this_date . $request->inner_image->getClientOriginalName();
                }

                


                DB::table('blog')->where('id', $request->id)->update(
                    [
                        'title' => $request->title,
                        'slug' => $request->slug,
                        'image' => $image,
                        'seo_title' => $request->seo_title ? $request->seo_title : '',
                        'seo_description' => $request->seo_description ? $request->seo_description : '',
                        'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                        'inner_image' => $inner_image,
                        'date' => $request->date ? $request->date : date('Y-m-d'),
                        'description' => $request->description ? $request->description : '',
                        'status' => $request->status,
                    ]
                );


                $request->session()->put('success', 'Successfully updated!');
            }
        }
        return redirect('/admin/blog/edit/' . $request->id)->with(['datas' => $data]);
    }

    public function blogPostAdd(Request $request)
    {
        $data = $_POST;
        if (empty($request->title) || empty($request->slug)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/blog/add/')->with(['datas' => $data]);
        } else {
            $db_link = DB::table('blog')->where('slug', '=', $request->slug)->count();
            if ($db_link > 0) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
                return redirect('/admin/blog/add/')->with(['datas' => $data]);
            } else {

                $this_date = date('YmdHis');

                $image = '';
                $inner_image = '';
                $author_photo = '';

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
                }

                $uploadedFile = $request->file('inner_image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('uploadfiles/', $this_date . $request->inner_image->getClientOriginalName());
                    }
                    $inner_image = '/uploadfiles/' . $this_date . $request->inner_image->getClientOriginalName();
                }

                

                DB::table('blog')->insert(
                    [
                        'title' => $request->title,
                        'slug' => $request->slug,
                        'image' => $image,
                        'seo_title' => $request->seo_title ? $request->seo_title : '',
                        'seo_description' => $request->seo_description ? $request->seo_description : '',
                        'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                        'inner_image' => $inner_image,
                        'date' => $request->date ? $request->date : date('Y-m-d'),
                        'description' => $request->description ? $request->description : '',
                        'status' => $request->status,
                    ]
                );


                $request->session()->put('success', 'Successfully added!');
                return redirect('/admin/blog/')->with(['datas' => $data]);
            }

        }
    }
}
