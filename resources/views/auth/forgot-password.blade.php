@extends('layouts.auth')

@section('title', __('Forgot Password'))

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

                <h1 class="auth-title">{{ __('Forgot Password.') }}</h1>

                <p class="auth-subtitle mb-3">
                    {{ __('Enter your email and we\'ll send your a link to reset your password.') }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <ul class="ms-0 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>
                                    <p>{{ $error }}</p>
                                </li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror"
                            placeholder="{{ __('E-Mail Address') }}" name="email" required autocomplete="current-email">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>

                    <button
                        class="btn btn-primary btn-block btn-lg shadow-lg mt-3">{{ __('Send Password Reset Link') }}</button>
                </form>

                <div class="text-center mt-4 text-lg fs-4">
                    <p class="text-gray-600">{{ __("Don't have an account") }}?
                        <a href="/register" class="font-bold">
                            {{ __('Sign up.') }}
                        </a>
                    </p>

                    <p class="text-gray-600">{{ __('Already have an account') }}?
                        <a href="/login" class="font-bold">{{ __('Log in.') }}</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

@endsection
