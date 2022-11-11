@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row " style="line-height: 3em;">
            <div class="col-md-9 col-sm-12 mt-auto mx-auto h3">{{$media->title}}</div>
            <div class="col-md-3 col-sm-12 mt-auto mx-auto">
                @if(!$media->zoho_addon_code)
                    <span class="text-success text-uppercase font-weight-bold h5">FREE</span>
                @elseif($isAddonPayed)
                    <span class="text-success text-uppercase font-weight-bold h5">PURCHASED</span>
                @else
                    <form action="{{ route('library.buyOneTimeAddon', $media->id) }}" method="POST" id="addAddonForm">
                        @csrf
                        @method('POST')
                        <a onclick="document.getElementById('addAddonForm').submit();" href="#" class="text-danger text-uppercase font-weight-bold h5">
                            BUY FOR {{$zohoAddon->price_brackets[0]['price']}} $
                        </a>
                    </form>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($media->image)
                    <img src="{{$media->image}}" alt="{{$media->title}}">
                @else
                    <video
                            id="video"
                            class="video-js col-sm-12 col-md-6"
                            controls
                            preload="auto"
                            width="640"
                            height="264"
                            poster="{{$media->thumbnail}}"
                            data-setup="{}"
                    >
                        <source src="{{$media->video}}" type="video/mp4"/>
                        <source src="MY_VIDEO.webm" type="video/webm"/>
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a
                            web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank"
                            >supports HTML5 video</a
                            >
                        </p>
                    </video>
                @endif
            </div>
            <div class="col-md-12 pt-4">
                <h4>Tags</h4>
                <div class="tags-list pt-2 font-weight-bold">
                    @if($media->tag_1)
                        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_1}}</span>
                    @endif

                    @if($media->tag_2)
                        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_2}}</span>
                    @endif
                    @if($media->tag_3)
                        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_3}}</span>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection