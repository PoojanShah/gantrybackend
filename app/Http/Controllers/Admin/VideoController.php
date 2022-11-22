<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VideoController extends BaseController
{
    public function video(Request $request)
    {
        $data = [];
        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';
        $data['user'] = $request->user();
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

        if (!Auth::user()->isSuperAdmin()) {
            $query = $query->leftJoin('customer_video', 'video.id', '=', 'customer_video.video_id')
                ->where('customer_video.customer_id', '=', Auth::user()->customer_id)
                ->orderBy('customer_video.customer_id', 'asc')
                ->orderBy('video.sort', 'asc');
        }

        $query = $query->orderBy('video.sort', 'asc');
        $video = $query->paginate($per_page);
        $count = $query->count();

        $data['video'] = $video;
        $data['count'] = $count;

        return view('admin.video.video', ['data' => $data]);
    }

    public function videoDelete()
    {
        $id = $_GET['id'];

        $video = DB::table('video')->where('id', '=', $id)->first();

        if (!empty($video->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->image)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->image);
        }
        if (!empty($video->video) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->video)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->video);
        }
        if (!empty($video->thumbnail) && file_exists($_SERVER['DOCUMENT_ROOT'] . $video->thumbnail)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $video->thumbnail);
        }

        DB::table('video')->delete($id);
    }

    public function videoEdit(Request $request)
    {
        $data['user'] = $request->user();
        $data['video'] = DB::table('video')->where('id', '=', $request->id)->first();
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

        return view('admin.video.videoEdit', ['data' => $data]);
    }

    public function videoPostEdit(Request $request)
    {
        $videoModel = Video::where('id', '=', $request->id)->first();
        $data = $_POST;
        $this_date = date('YmdHis');
        $video = $videoModel->video;
        $thumbnail = $videoModel->thumbnail;

        $uploadedFile2 = $request->file('video');
        if ($uploadedFile2 && $uploadedFile2->isValid()) {

            if (file_exists(public_path($videoModel->video))) {
                unlink(public_path($videoModel->video));
            }

            $videoName = $this_date . '_' . $this->sanitizeTitle($request->title) . '.' . $uploadedFile2->getClientOriginalExtension();
            $uploadedFile2->move('uploadfiles/', $videoName);
            $video = '/uploadfiles/' . $videoName;

            if((file_exists(public_path($videoModel->thumbnail)))){
                $thumbnail = $this->renameFile($videoModel->thumbnail, $this_date);
            }

        }

        $uploadedFile3 = $request->file('thumbnail');
        if ($uploadedFile3 && $uploadedFile3->isValid()) {

            if (file_exists(public_path($videoModel->thumbnail))) {
                unlink(public_path($videoModel->thumbnail));
            }

            $thumbName = $this_date . '_thumb-' . $this->sanitizeTitle($request->title) . '.' . $uploadedFile3->getClientOriginalExtension();
            $uploadedFile3->move('uploadfiles/', $thumbName);
            $thumbnail = '/uploadfiles/' . $thumbName;

            if((file_exists(public_path($videoModel->video)))){
                $video = $this->renameFile($videoModel->video, $this_date);
            }

        }

        $videoModel->video = $video;
        $videoModel->thumbnail = $thumbnail;
        $videoModel->zoho_addon_code = $request->zoho_addon_code;
        $videoModel->sort = $request->sort ? $request->sort : 0;
        $videoModel->status = $request->status;
        $videoModel->title = $request->title;
        $videoModel->tag_1 = (!empty($request->tag_1)) ? $request->tag_1 : '';
        $videoModel->tag_2 = (!empty($request->tag_2)) ? $request->tag_2 : '';
        $videoModel->tag_3 = (!empty($request->tag_3)) ? $request->tag_3 : '';
        $videoModel->save();

        $request->session()->put('success', 'Successfully updated!');

        return redirect('/admin/video/edit/' . $request->id)->with(['datas' => $data]);
    }

    public function videoAdd(Request $request)
    {
        $data['user'] = $request->user();
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

        return view('admin.video.videoAdd', ['data' => $data]);
    }

    public function videoPostAdd(Request $request)
    {
        $data = $_POST;
        $this_date = date('YmdHis');
        $video = '';
        $thumbnail = '';

        if ($uploadedFile2 = $request->file('video')) {
            if ($uploadedFile2->isValid()) {
                $videoName = $this_date . '_' . $this->sanitizeTitle($request->title) . '.' . $uploadedFile2->getClientOriginalExtension();
                $uploadedFile2->move('uploadfiles/', $videoName);
                $video = '/uploadfiles/' . $videoName;
            }
        }

        if ($uploadedFile3 = $request->file('thumbnail')) {
            if ($uploadedFile3->isValid()) {
                $thumbName = $this_date . '_thumb-' . $this->sanitizeTitle($request->title) . '.' . $uploadedFile3->getClientOriginalExtension();
                $uploadedFile3->move('uploadfiles/', $thumbName);
                $thumbnail = '/uploadfiles/' . $thumbName;
            }
        }

        DB::table('video')->insert(
            [
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

        return redirect('/admin/video/')->with(['datas' => $data]);

    }

    private function sanitizeTitle(string $title): string
    {
        return htmlspecialchars(str_replace(' ', '_', $title));
    }

    private function renameFile(string $originalPath, string $prefix): string
    {
        $newName = preg_replace('/\d*_/', $prefix.'_$1', $originalPath);
        rename(public_path($originalPath),  public_path($newName));

        return $newName;
    }

}
