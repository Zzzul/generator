@extends('layouts.auth')

@section('title', __('Confirm Password'))

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/pages/auth.css">
@endpush

@section('content')
    <div class="row h-100">
        <div class="col-lg-7 col-12">
            <div id="auth-left">
                <div class="auth-logo" class="mb-0">
                    <a href="/">
                        <img src="{{ asset('mazer') }}/images/logo/logo.svg" alt="Logo">
                    </a>
                </div>

                <h1 class="auth-title">{{ __('Confirm Password.') }}</h1>

                <p class="auth-subtitle mb-3">{{ __('Confirm your password to proceed to the next step.') }}</p>

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

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                            placeholder="Password" name="password" required autocomplete="current-password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">{{ __('Confirm Password') }}</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

@endsection
