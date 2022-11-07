@include('admin.header')

<div class="container">
    <div class="card">
        <div class="card-header">Update Administrator Profile
            <?php if(!empty($success)) { ?>
            <p class="success" style="color: #008000;"><?= $success; ?></p>
            <?php } ?>
            <?php if(!empty($error)) { ?>
            <p class="success" style="color: #ff0000;"><?= $error; ?></p>
            <?php } ?>
        </div>


        <div class="card-body">
            <form method="GET" action="{{ route('profile') }}" style="width: 60%; margin: 0 auto;">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="<?= $user->email; ?>" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.footer')
