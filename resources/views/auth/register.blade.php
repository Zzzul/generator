@extends('layouts.auth')

@section('title', __('Sign Up'))

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/pages/auth.css">
@endpush

@section('content')
    <div class="row h-100">
        <div class="col-lg-7 col-12">
            <div id="auth-left">
                <div class="auth-logo" class="mb-0">
                    <a href="/"><img src="{{ asset('mazer') }}/images/logo/logo.svg" alt="Logo"></a>
                </div>

                <h1 class="auth-title">{{ __('Sign Up.') }}</h1>
                <p class="auth-subtitle mb-3">{{ __('Input your data to register to our website.') }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <ul class="ms-0 mb-0">
                        @foreach ($errors->all() as $error)
                            <li><p>{{ $error }}</p></li>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" name="name"
                            class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Name"
                            value="{{ old('name') }}" autofocus>
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email"
                            class="form-control form-control-xl @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Username">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password"
                            class="form-control form-control-xl @error('password') is-invalid @enderror"
                            placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password_confirmation" class="form-control form-control-xl"
                            placeholder="Confirm Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">{{ __('Sign Up') }}</button>
                </form>

                <div class="text-center mt-4 text-lg fs-4">
                    <p class="text-gray-600">{{ __('Already have an account') }}?
                        <a href="/login" class="font-bold">{{ __('Log in.') }}</a>
                    </p>

                    @if (Route::has('password.request'))
                        <p>
                            <a class="font-bold" href="{{ route('password.request') }}">
                                {{ __('Forgot password?.') }}
                            </a>
                        </p>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

@endsection
