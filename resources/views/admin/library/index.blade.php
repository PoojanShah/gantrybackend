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
                <div class="col-md-3"
                     data-bs-toggle="modal"
                     data-bs-target="#mediaPreview"
                     data-bs-type="{{$media->video ? 'video' : 'image'}}"
                     data-bs-path="{{$media->video ?? $addon->image}}"
                     data-bs-title="{{$media->title}}"
                >
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start">
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    <div class="media-free font-weight-bold text-capitalize pt-2">FREE</div>
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
            @endforeach

        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h5 class="font-weight-bold">Paid</h5>
            </div>
            @foreach($subscription->addons as $addon)
                <div class="col-md-3"
                     data-bs-toggle="modal"
                     data-bs-target="#mediaPreview"
                     data-bs-type="{{$addon->video->video ? 'video' : 'image'}}"
                     data-bs-path="{{$addon->video->video ?? $addon->video->image}}"
                     data-bs-title="{{$addon->name}}"
                >
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
                </div>
            @endforeach

        </div>
    </div>

    <!-- MODAL TO BUY-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="mediaPreview" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPreviewLabel">TITLE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video src="SRC"></video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Buy</button>
                </div>
            </div>
        </div>
    </div>
@endsection
