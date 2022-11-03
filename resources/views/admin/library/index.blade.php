@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h3 class="text-center">{{ __('Media Library') }}</h3>
            </div>
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

        </div>
        <div class="row pb-2">
            <div class="col-md-12 pt-2 pb-2">
                <h5 class="font-weight-bold">Default</h5>
            </div>
            @foreach($freeMedia as $media)
                <a class="col-md-3" href="{{route('library.show', $media->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start">
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    <div class="media-free font-weight-bold text-capitalize pt-2">FREE</div>
                    <div class="tags-list pt-2 font-weight-bold">
                        @if($media->tag_1)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_1}}</span>
                        @endif

                        @if($media->tag_2)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_2}}</span>
                        @endif
                        @if($media->tag_3)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_3}}</span>
                        @endif

                    </div>
                </a>
            @endforeach

        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h5 class="font-weight-bold">Paid</h5>
            </div>
            @foreach($subscription->addons as $addon)
                <a class="col-md-3" href="{{route('library.show', $addon->video->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$addon->video->thumbnail}}" class="img-thumbnail float-start">
                    </div>
                    <div class="font-weight-bold pt-2">{{$addon->name}}</div>
                    <div class="media-price font-weight-bold pt-2">{{$addon->total}} {{$subscription->currency_code}}</div>
                    <div class="tags-list pt-2 font-weight-bold">
                        @if($addon->video->tag_1)
                            <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$addon->video->tag_1}}</span>
                        @endif

                        @if($addon->video->tag_2)
                            <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$addon->video->tag_2}}</span>
                        @endif
                        @if($addon->video->tag_3)
                            <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$addon->video->tag_3}}</span>
                        @endif

                    </div>
                </a>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h5 class="font-weight-bold">Available for subscription</h5>
            </div>
            @foreach($availableForSubscription as $media)
                <a class="col-md-3" href="{{route('library.show', $media->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start">
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    <div class="media-free font-weight-bold text-capitalize pt-2">FREE</div>
                    <div class="tags-list pt-2 font-weight-bold">
                        @if($media->tag_1)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_1}}</span>
                        @endif

                        @if($media->tag_2)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_2}}</span>
                        @endif
                        @if($media->tag_3)
                            <span class="media-tag btn btn-sm btn-rounded btn-outline-secondary">{{$media->tag_3}}</span>
                        @endif

                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endsection
