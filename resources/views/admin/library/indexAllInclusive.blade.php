@extends('layouts.app')
@section('content')
    <div>
        <div class="row justify-content-center library-title-block">
            <div class="col-md-12">
                <h3 class="text-center library-title">{{ __('Media Library') }}</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">

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
                <h5 class="font-weight-bold">Available Media</h5>
            </div>
            @foreach($media as $singleMedia)
                <a class="col-md-3 media-item" href="{{route('library.show', $singleMedia->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$singleMedia->thumbnail}}" class="img-thumbnail float-start border-0">
                        @include('admin.library.mediaBadge', ['media' => $singleMedia])
                    </div>
                    <div class="font-weight-bold pt-2">{{$singleMedia->title}}</div>
                    @include('admin.library.tagsList', ['media' => $singleMedia])
                </a>
            @endforeach

        </div>
    </div>
@endsection
