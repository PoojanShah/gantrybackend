<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
        $data = [];

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs(__('Video'), $href);

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
        $query = DB::table('video');

        if (!Auth::user()->isSuperAdmin()){
            $query = $query->leftJoin('customer_video', 'video.id', '=', 'customer_video.video_id')
                ->where('customer_video.customer_id', '=', Auth::user()->customer_id)
                ->orderBy('customer_video.customer_id', 'asc')
                ->orderBy('video.sort', 'asc');
        }

        $query = $query->orderBy('video.sort', 'asc');
        $video = $query->paginate($per_page);
        $count = $query->count();

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
        if(!empty($video->thumbnail) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->thumbnail)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->thumbnail);
        }

        DB::table('video')->delete($id);
    }

    public function videoEdit(Request $request) {

        $data['user'] = $request->user();

        $data['video'] = DB::table('video')->where('id', '=', $request->id)->first();

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
                $uploadedFile->move('uploadfiles/', $this_date. '_' . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date. '_' . $request->image->getClientOriginalName();
        }

        $video = $db_link->video;

        $uploadedFile2 = $request->file('video');
        if (isset($uploadedFile2)) {
            if ($uploadedFile2->isValid()) {
                $uploadedFile2->move('uploadfiles/', $this_date. '_' . $request->video->getClientOriginalName());
            }
            $video = '/uploadfiles/' . $this_date. '_' . $request->video->getClientOriginalName();
        }

        $thumbnail = $db_link->thumbnail;


        $uploadedFile3 = $request->file('thumbnail');
        if (isset($uploadedFile3)) {
            if ($uploadedFile3->isValid()) {
                $uploadedFile3->move('uploadfiles/', $this_date. '_' . $request->thumbnail->getClientOriginalName());
            }
            $thumbnail = '/uploadfiles/' . $this_date. '_' . $request->thumbnail->getClientOriginalName();
        }


        DB::table('video')->where('id', $request->id)->update(
            [
                'image' => $image,
                'video' => $video,
                'thumbnail' => $thumbnail,
                'zoho_addon_code' => $request->zoho_addon_code,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'tag_1' => (!empty($request->tag_1)) ? $request->tag_1 : '',
                'tag_2' => (!empty($request->tag_2)) ? $request->tag_2 : '',
                'tag_3' => (!empty($request->tag_3)) ? $request->tag_3 : '',
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
        $thumbnail = '';

        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date. '_' . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date. '_' . $request->image->getClientOriginalName();
        }

        $uploadedFile2 = $request->file('video');
        if (isset($uploadedFile2)) {
            if ($uploadedFile2->isValid()) {
                $uploadedFile2->move('uploadfiles/', $this_date. '_' . $request->video->getClientOriginalName());
            }
            $video = '/uploadfiles/' . $this_date. '_' . $request->video->getClientOriginalName();
        }

        $uploadedFile3 = $request->file('thumbnail');
        if (isset($uploadedFile3)) {
            if ($uploadedFile3->isValid()) {
                $uploadedFile3->move('uploadfiles/', $this_date. '_' . $request->thumbnail->getClientOriginalName());
            }
            $thumbnail = '/uploadfiles/' . $this_date. '_' . $request->thumbnail->getClientOriginalName();
        }

        DB::table('video')->insert(
            [
                'image' => $image,
                'video' => $video,
                'thumbnail' => $thumbnail,
                'sort' => $request->sort ? $request->sort : 0,
                'zoho_addon_code' => $request->zoho_addon_code,
                'status' => $request->status,
                'title' => $request->title,
                'tag_1' => (!empty($request->tag_1)) ? $request->tag_1 : '',
                'tag_2' => (!empty($request->tag_2)) ? $request->tag_2 : '',
                'tag_3' => (!empty($request->tag_3)) ? $request->tag_3 : '',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
            ]
        );

        $request->session()->put('success', 'Successfully added!');
        return redirect('/admin/video/')->with( ['datas' => $data] );

    }

}
