@include('admin.header')

<div class="container">
    <div class="row">
        <h4 class="col-12">Token generation</h4>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (isset($data['accessToken']))
            <div class="col-12">
                <div>
                    <strong>Access Token</strong> {{ $data['accessToken'] }}
                </div>
                <div>
                    <strong>Refresh Token</strong> {{ $data['refreshToken'] }}
                </div>
            </div>

        @else
            <div class="col-12">
                <div class="card">
                    <form action="" method="get" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="card-body row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <strong>Current Refresh Token:</strong> {{ $data['refreshToken'] }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Code</label>
                                    <p class="small">
                                        Code might be taken from <a href="https://api-console.zoho.com">https://api-console.zoho.com</a>
                                        Then go to "Self Client", generate code with scope <b>ZohoSubscriptions.fullaccess.all</b>
                                    </p>
                                    <input type="text" name="code" class="form-control" id="name"
                                           placeholder="Code"
                                           value="" required>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        @endif

    </div>
</div>
</div>

@include('admin.footer')
