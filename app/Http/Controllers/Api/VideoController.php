<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class VideoController extends BaseController
{
    public function getVideos(Request $request, Video $videoModel)
    {
        foreach ($videoModel->getAvailableVideosObjects($request->header('InstallationId')) as $video) {
            $tags = [];
            if (!empty($video->tag_1)) {
                $tags[] = $video->tag_1;
            }
            if (!empty($video->tag_2)) {
                $tags[] = $video->tag_2;
            }
            if (!empty($video->tag_3)) {
                $tags[] = $video->tag_3;
            }

            $data[] = [
                'id' => $video->id,
                'title' => $video->title,
                //'image' => (!empty($video->image)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->image : '',
                'thumbnail' => (!empty($video->thumbnail)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->thumbnail : '',
                'media' => (!empty($video->video)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->video : '',
                'tags' => $tags,
                'sort' => $video->sort,
                'created_at' => $video->created_at,
                'updated_at' => $video->updated_at,
            ];
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function getMedia(Request $request, Video $videoModel)
    {
        foreach ($videoModel->getAvailableVideosObjects($request->header('InstallationId')) as $video) {
            $data[] = [
                'thumbnail' => (!empty($video->thumbnail)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->thumbnail : '',
                'media' => (!empty($video->video)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->video : '',
                'tags' => $this->getTagsArray($video),
            ];
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function getMessages()
    {
        return response()->json(['test' => true], Response::HTTP_OK);
    }
}