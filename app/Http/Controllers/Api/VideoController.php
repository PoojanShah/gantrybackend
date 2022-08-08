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
                        'image' => (!empty($video->image)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->image : '',
                        'video' => (!empty($video->video)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->video : '',
                        'sort' => $video->sort,
                    ];
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json(['Unauthorized'], 401);
        }
    }
}