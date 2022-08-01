@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @guest
                    <div class="card-header">
                        <p>Administrator password recovery</p>
                        <p>Enter Administrator Email. An email will be sent to it with a link to reset your password.</p>
                        <?php if(!empty($success)) { ?>
                        <p class="success" style="color: #008000;"><?= $success; ?></p>
                        <?php } ?>
                        <?php if(!empty($error)) { ?>
                        <p class="success" style="color: #ff0000;"><?= $error; ?></p>
                        <?php } ?>
                    </div>


                    <div class="card-body">
                        <form method="GET" action="{{ route('forgot') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                        <div class="card-header">
                            <p>Administrator password recovery</p>
                            <p style="color:#ff0000;">You are already logged in. Change the password at <a href="/profile">profile</a></p>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
