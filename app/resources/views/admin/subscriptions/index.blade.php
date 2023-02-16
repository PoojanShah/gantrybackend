@extends('layouts.app')
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h3 class="text-center">{{ __('Subscriptions') }}</h3>
                </div>
                <div class="col-md-12">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table align-self-center table-responsive-sm">
                        <thead>
                        <tr>
                            <th class="text-center" scope="col">Plan</th>
                            <th class="text-center" scope="col">Next billing</th>
                            <th class="text-center" scope="col">Expires at</th>
                            <th class="text-center" scope="col">Status</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subscriptions as $subscription)
                            <tr>
                                <td class="text-center" scope="col">{{ $subscription->plan_name }}</td>
                                <td class="text-center" scope="col">
                                    {{ $subscription->next_billing_at ? $subscription->next_billing_at->format('d/m/Y') : '' }}
                                </td>
                                <td class="text-center" scope="col">
                                    {{ $subscription->expires_at ? $subscription->expires_at->format('d/m/Y') : '' }}
                                </td>
                                <td class="text-center" scope="col">{{ $subscription->status }}</td>
                                <td class="text-center" scope="col">
{{--                                    <a href="{{ route('admin.subscriptions.edit', $subscription)}}" class="bth btn-info form-control">Activate/Cancel</a>--}}
{{--                                    <form action="{{ route('admin.subscriptions.delete', $subscription) }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <input type="submit" class="btn btn-danger form-control" value="Remove">--}}
{{--                                    </form>--}}
                                    <a href="{{ route('subscriptions.show', $subscription->subscription_id) }}" class="btn btn-outline-success form-control">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
@endsection
