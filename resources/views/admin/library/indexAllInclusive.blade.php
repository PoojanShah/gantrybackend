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
                <h5 class="font-weight-bold">Available Media</h5>
            </div>
            @foreach($media as $singleMedia)
                <a class="col-md-3" href="{{route('library.show', $singleMedia->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$singleMedia->thumbnail}}" class="img-thumbnail float-start">
                    </div>
                    <div class="font-weight-bold pt-2">{{$singleMedia->title}}</div>
                    @include('admin.library.tagsList', ['media' => $singleMedia])
                </a>
            @endforeach

        </div>
    </div>
@endsection
