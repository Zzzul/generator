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

                <h1 class="auth-title">{{ __('Two Factor Challenge.') }}</h1>

                <p class="auth-subtitle mb-3">{{ __('Enter the authentication code to log in.') }}</p>

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

                <form method="POST" action="{{ route('two-factor.login') }}">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('code') is-invalid @enderror"
                            placeholder="Code" name="code" autocomplete="code">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-text">{{ __('Or you can enter one of the recovery codes') }}</div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('recovery_code') is-invalid @enderror"
                            placeholder="Recovery Code" name="recovery_code" autocomplete="recovery_code">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

@endsection
