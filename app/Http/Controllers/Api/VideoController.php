<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VideoController extends BaseController
{
    public function getVideos(Request $request)
    {
        $token = $request->token;
        if($token === env('API_TOKEN')) {
            $videos = DB::table('video')->where(['status' => 1])->orderBy('sort')->get();
            $data = [];
            if(!empty($videos)) {
                foreach ($videos as $video) {
                    $data[] = [
                        'id' => $video->id,
                        'title' => $video->title,
                        //'image' => (!empty($video->image)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->image : '',
                        'thumbnail' => (!empty($video->thumbnail)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->thumbnail : '',
                        'media' => (!empty($video->video)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->video : '',
                        'sort' => $video->sort,
                        'created_at' => $video->created_at,
                        'updated_at' => $video->updated_at,
                    ];
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json(['Unauthorized'], 401);
        }
    }

    public function getMedia(Request $request)
    {
        $token = $request->token;
        if($token === env('API_TOKEN')) {
            $videos = DB::table('video')->where(['status' => 1])->orderBy('sort')->get();
            $data = [];
            if(!empty($videos)) {
                foreach ($videos as $video) {
                    $data[] = [
                        //'id' => $video->id,
                        //'title' => $video->title,
                        //'image' => (!empty($video->image)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->image : '',
                        'thumbnail' => (!empty($video->thumbnail)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->thumbnail : '',
                        'media' => (!empty($video->video)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->video : '',
                        //'sort' => $video->sort,
                        //'created_at' => $video->created_at,
                        //'updated_at' => $video->updated_at,
                    ];
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json(['Unauthorized'], 401);
        }
    }

    public function getMessages(Request $request)
    {
        $token = $request->token;
        if ($token === env('API_TOKEN')) {
            $data = ['test' => true];
            return response()->json($data, 200);
        } else {
            return response()->json(['Unauthorized'], 401);
        }
    }
}