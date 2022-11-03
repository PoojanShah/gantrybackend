<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class VideoController extends BaseController
{
    public function getVideos(Request $request, Video $videoModel, Customer $customerModel)
    {
        $data = [];
        $installationId = $request->header('InstallationId');

        if ($installationId && !$customerModel->getActiveSubscriptionByInstallationId($installationId)) {
            return $data;
        }

        foreach ($videoModel->getAvailableVideosObjects($installationId) as $video) {
            $data[] = [
                'id' => $video->id,
                'title' => $video->title,
                //'image' => (!empty($video->image)) ? 'https://'.$_SERVER['HTTP_HOST'].$video->image : '',
                'thumbnail' => (!empty($video->thumbnail)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->thumbnail : '',
                'media' => (!empty($video->video)) ? 'https://' . $_SERVER['HTTP_HOST'] . $video->video : '',
                'tags' => $this->getTagsArray($video),
                'sort' => $video->sort,
                'created_at' => $video->created_at,
                'updated_at' => $video->updated_at,
            ];
        }

        return $data;
    }

    public function getMedia(Request $request, Video $videoModel, Customer $customerModel)
    {
        $data = [];
        $installationId = $request->header('InstallationId');

        if ($installationId && !$customerModel->getActiveSubscriptionByInstallationId($installationId)) {
            return $data;
        }

        foreach ($videoModel->getAvailableVideosObjects($installationId) as $video) {
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

    public function getTagsArray($media): array
    {
        $tags = [];
        if (!empty($media->tag_1)) {
            $tags[] = $media->tag_1;
        }
        if (!empty($media->tag_2)) {
            $tags[] = $media->tag_2;
        }
        if (!empty($media->tag_3)) {
            $tags[] = $media->tag_3;
        }

        return $tags;
    }
}