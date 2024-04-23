@extends('layouts.auth')

<head>
    <title>Login</title>
</head>

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="text-center">
                                <img class="img-fluid" src="<?php echo asset('storage/KK_GROUP_LOGO.png') ?>">
                            </div>
                            <div class="p-4">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Login') }}</h1>
                                </div>

                                @if ($errors->any())
                                <div class="alert alert-danger border-left-danger" role="alert">
                                    <ul class="pl-4 my-2">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Form for insert the username and password -->
                                <form method="POST" action="{{ route('login') }}" class="user">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="form-group">
                                        <input type="pass_no" class="form-control form-control-user" name="pass_no" placeholder="{{ __('Username') }}" value="{{ old('pass_no') }}" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password" placeholder="{{ __('Password') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                        </div>
                                    </div>

                                    <!-- authorisation part -->
                                    <!-- <div class="form-group"> -->
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Login') }}
                                    </button>
                            </div>

                            <hr>

                            <!--                                    <div class="form-group">
                                        <button type="button" class="btn btn-github btn-user btn-block">
                                            <i class="fab fa-github fa-fw"></i> {{ __('Login with GitHub') }}
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-twitter btn-user btn-block">
                                            <i class="fab fa-twitter fa-fw"></i> {{ __('Login with Twitter') }}
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> {{ __('Login with Facebook') }}
                                        </button>
                                    </div>-->
                            </form>

                            <hr>

                            @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            </div>
                            @endif

                            @if (Route::has('register'))
                            <div class="text-center">
                                <a class="small" href="{{ route('register') }}">{{ __('Create an Account!') }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection