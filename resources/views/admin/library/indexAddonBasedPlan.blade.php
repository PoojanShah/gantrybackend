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
                    <div class="alert alert-{{session('status')}}" role="alert">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

        </div>
        <div class="row pb-2">
            <div class="col-md-12 pt-2 pb-2">
                <h4 class="font-weight-bold pt-2 pb-2">Default</h4>
            </div>
            @foreach($freeMedia as $media)
                <a class="col-md-3  media-item" href="{{route('library.show', $media->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start border-0">
                        @include('admin.library.mediaBadge', ['media' => $media])
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    <div class="media-free font-weight-bold text-capitalize pt-2">FREE</div>
                    @include('admin.library.tagsList', ['media' => $media])
                </a>
            @endforeach

        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h4 class="font-weight-bold pt-2 pb-2">Purchased</h4>
            </div>
            @foreach($purchasedMedia as $media)
                <a class="col-md-3 media-item" href="{{route('library.show', $media->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$media->thumbnail}}" class="img-thumbnail float-start border-0">
                        @include('admin.library.mediaBadge', ['media' => $media])
                    </div>
                    <div class="font-weight-bold pt-2">{{$media->title}}</div>
                    @include('admin.library.tagsList', ['media' => $media])
                </a>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                <div class="subscription-tip card">
                    <div class="card-header font-weight-bold">
                        Tip
                    </div>
                    <div class="card-body text-muted">
                        <div>
                            You can change your subscription plan to "All inclusive" and get access to all videos! <br>
                            Just contact us by email:
                            <a href = "mailto:@php echo \Illuminate\Support\Facades\Config::get('app.ADMIN_EMAIL'); @endphp">
                                @php echo \Illuminate\Support\Facades\Config::get('app.ADMIN_EMAIL'); @endphp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 pt-5 pb-2">
                <h4 class="font-weight-bold pt-2 pb-2">Available for subscription</h4>
            </div>

            @foreach($availableForPurchasingAddons as $addon)
                <a class="col-md-3 media-item" href="{{route('library.show', $addon->video->id)}}">
                    <div class="media-thumbnail">
                        <img src="{{$addon->video->thumbnail}}" class="img-thumbnail float-start border-0">
                        @include('admin.library.mediaBadge', ['media' => $addon->video])
                    </div>
                    <div class="font-weight-bold pt-2">{{$addon->video->title}}</div>
                    <div class="media-price font-weight-bold pt-2"><small
                                class="font-weight-bold">{{$subscription->currency_symbol}}</small> {{$addon->price_brackets[0]['price']}}
                    </div>
                    @include('admin.library.tagsList', ['media' => $addon->video])
                </a>
            @endforeach

        </div>
    </div>
@endsection
