@extends('layouts.app')

@section('content')

    @php $addonsTotal =0 @endphp

    <div class="container">
        <div class="row justify-content-end font-weight-bold text-right">
            <div class="col-xs-12 col-sm-3">
                @csrf
                @method('POST')
                @if($subscription->status === 'live')
                    <div class="btn btn-md btn-danger font-weight-bold" data-toggle="popover" title="Cancel"
                         data-content="To cancel subscription contact us by email:
                        @php
                             echo \Illuminate\Support\Facades\Config::get('app.ADMIN_EMAIL');
                         @endphp">
                        Cancel
                    </div>
                @endif
            </div>
        </div>
        <div class="row">

            <div class="col-12 mb-2">
                <h3 class="text-center">{{__($subscription->name) }} </h3>
            </div>
            <h6 class="col-12 mb-2 mt-2">
                Billing Details
            </h6>

            <div class="col-sm-3">
                <div class="text-muted">Activation Date :</div>
                <div class="">{{ $subscription->activated_at ? $subscription->activated_at->format('d/m/Y') : '---'}}</div>
            </div>
            <div class="col-sm-3">
                <div class="text-muted">Previous Billing Date :</div>
                <div class="">{{ $subscription->last_billing_at ? $subscription->last_billing_at->format('d/m/Y') : '---' }}</div>
            </div>
            <div class="col-sm-3">
                <div class="text-muted">Expiration Date :</div>
                <div class="">{{ $subscription->expires_at ? $subscription->expires_at->format('d/m/Y') : '---' }}</div>
            </div>
            <div class="col-sm-3">
                <div class="text-muted">Status</div>
                <div class="">{{ $subscription->status}}</div>
            </div>

            <h6 class="col-12 mb-2 mt-4">
                Subscription Details
            </h6>
            <div class="col-md-12 card">
                <div class="card-body">
                    <div class="row font-weight-bold">
                        <div class="col-4">Item</div>
                        <div class="col-4">Discount</div>
                        <div class="col-4">Rate</div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 card">
                <div class="card-header">
                    PLAN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">{{$subscription->plan->name}}</div>
                        <div class="col-4">{{$subscription->plan->discount ?? 0}}</div>
                        <div class="col-4">{{$subscription->plan->price}} {{$subscription->currency_code}}</div>
                    </div>
                </div>
            </div>
            @if(count($subscription->addons)  > 0 )
                <div class="col-md-12 card">
                    <div class="card-header">
                        ADDONS
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($subscription->addons as $addon)
                                @php $addonsTotal +=$addon->total @endphp
                                <div class="col-4">
                                    {{$addon->name}}
                                    <br>
                                    <span class="text-muted small">{{$addon->description}}</span>
                                </div>
                                <div class="col-4">{{$addon->discount ?? 0}}</div>
                                <div class="col-4">{{$addon->price}} {{$subscription->currency_code}}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row justify-content-end ">
            <div class="col-sm-6 col-xs-12">
                <div class="row font-weight-bold text-center">
                    <div class="col-xs-12 col-sm-3">Total :</div>
                    <div class="col-xs-12 col-sm-3">{{$subscription->amount + $addonsTotal}} {{$subscription->currency_code}} </div>
                </div>
            </div>
        </div>
    </div>
@endsection