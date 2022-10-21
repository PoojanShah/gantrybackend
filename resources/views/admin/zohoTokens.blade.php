@include('admin.header')

<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
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
                <div class="col-12">
                    @if (isset($data['accessToken']))
                        <div>
                            <strong>Access Token</strong> {{ $data['accessToken'] }}
                        </div>
                        <div>
                            <strong>Refresh Token</strong> {{ $data['refreshToken'] }}
                        </div>
                    @else
                        <div>
                            <div class="card">
                                <form action="" method="get" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <div class="card-body row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Code</label>
                                                <p class="small">
                                                    Code might be taken from  <a href = "https://api-console.zoho.com">https://api-console.zoho.com</a>
                                                    Then go to "Self Client", generate code with scope <b>ZohoSubscriptions.fullaccess.all</b>
                                                </p>
                                                <input type="text" name="code" class="form-control" id="name" placeholder="Code" value="" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')
