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

class VideoController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function video(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Video';
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

        $video = DB::table('video')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('video')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['video'] = $video;
        $data['count'] = $count;

        return view('admin.video.video', ['data' => $data]);
    }

    public function videoDelete()
    {
        $id = $_GET['id'];

        $video = DB::table('video')->where('id', '=', $id)->first();

        if(!empty($video->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->image)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->image);
        }
        if(!empty($video->video) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->video)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->video);
        }

        DB::table('video')->delete($id);
    }

    public function videoEdit(Request $request) {

        $data['user'] = $request->user();

        $data['video'] = DB::table('video')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Video';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Video', $href);

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
        return view('admin.video.videoEdit', ['data' => $data]);
    }

    public function videoPostEdit(Request $request) {
        $data = $_POST;

        $db_link = DB::table('video')->where('id', '=', $request->id)->first();
        $this_date = date('YmdHis');

        $image = $db_link->image;


        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
        }

        $video = $db_link->video;

        $uploadedFile2 = $request->file('video');
        if (isset($uploadedFile2)) {
            if ($uploadedFile2->isValid()) {
                $uploadedFile2->move('uploadfiles/', $this_date . $request->video->getClientOriginalName());
            }
            $video = '/uploadfiles/' . $this_date . $request->video->getClientOriginalName();
        }


        DB::table('video')->where('id', $request->id)->update(
            [
                'image' => $image,
                'video' => $video,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'updated_at' => DB::raw('now()'),
            ]
        );


        $request->session()->put('success', 'Successfully updated!');

        return redirect('/admin/video/edit/'.$request->id)->with( ['datas' => $data] );
    }

    public function videoAdd(Request $request) {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Video';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Video', $href);

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
        return view('admin.video.videoAdd', ['data' => $data]);
    }

    public function videoPostAdd(Request $request) {
        $data = $_POST;

        $this_date = date('YmdHis');

        $image = '';
        $video = '';

        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
        }

        $uploadedFile2 = $request->file('video');
        if (isset($uploadedFile2)) {
            if ($uploadedFile2->isValid()) {
                $uploadedFile2->move('uploadfiles/', $this_date . $request->video->getClientOriginalName());
            }
            $video = '/uploadfiles/' . $this_date . $request->video->getClientOriginalName();
        }

        DB::table('video')->insert(
            [
                'image' => $image,
                'video' => $video,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
            ]
        );

        $request->session()->put('success', 'Successfully added!');
        return redirect('/admin/video/')->with( ['datas' => $data] );

    }

}
