@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h3 class="text-center">{{ __('Media Library') }}</h3>
            </div>
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-{{session('status')}}" role="alert">
                        {{ session('message') }}
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
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start border-0">
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    <div class="media-free font-weight-bold text-capitalize pt-2">FREE</div>
                    @include('admin.library.tagsList', ['media' => $media])
                </a>
            @endforeach

        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h5 class="font-weight-bold">Purchased</h5>
            </div>
            @foreach($purchasedMedia as $media)
                <a class="col-md-3" href="{{route('library.show', $media->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start border-0">
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    @include('admin.library.tagsList', ['media' => $media])
                </a>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h5 class="font-weight-bold">Available for subscription</h5>
            </div>

            @foreach($availableForPurchasingAddons as $addon)
                <a class="col-md-3" href="{{route('library.show', $addon->video->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$addon->video->thumbnail}}" class="img-thumbnail float-start border-0">
                    </div>
                    <div class="font-weight-bold pt-2">{{$addon->video->title}}</div>
                    <div class="media-price font-weight-bold pt-2">{{$addon->price_brackets[0]['price']}} {{$subscription->currency_code}}</div>
                    @include('admin.library.tagsList', ['media' => $addon->video])
                </a>
            @endforeach

        </div>
    </div>
@endsection