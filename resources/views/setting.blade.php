@extends('layouts.app')

@section('title', trans('Setting'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Setting') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Change your profile information, password and enable/disable two factor authentication.') }}
                    </p>
                </div>
                <div class="col-12 col-md-4 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section mt-4">
            {{-- Profile --}}
            <div class="row">
                <div class="col-md-3">
                    <h4>{{ __('Profile') }}</h4>
                </div>
                <div class="col-md-9">
                    @if (session('status') == 'profile-information-updated')
                        <div class="alert alert-success alert-dismissible show fade">
                            {{ __('Profile information updated successfully.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user-profile-information.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="email">{{ __('E-mail Address') }}</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        placeholder="{{ __('E-mail Address') }}"
                                        value="{{ old('email') ?? auth()->user()->email }}" required>

                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror"
                                        id="name" placeholder="{{ __('Name') }}"
                                        value="{{ old('name') ?? auth()->user()->name }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="row">
                <div class="col-md-12">
                    <hr class="mb-5">
                </div>

                <div class="col-md-3">
                    <h4>{{ __('Change Password') }}</h4>
                </div>
                <div class="col-md-9">
                    @if (session('status') == 'password-updated')
                        <div class="alert alert-success alert-dismissible show fade">
                            {{ __('Password updated successfully.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user-password.update') }}">
                                @csrf
                                @method('put')
                                <div class="form-group mb-3">
                                        <label for="password">{{ __('Current Password') }}</label>
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                            id="password" placeholder="••••••••" required>
                                        @error('current_password', 'updatePassword')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                </div>

                                <div class="form-group mb-3">
                                        <label for="password">{{ __('New Password') }}</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                            id="password" placeholder="••••••••" required>
                                        @error('password', 'updatePassword')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                </div>
                                
                                <div class="form-group mb-3">
                                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="••••••••" required>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('Change Password') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2FA --}}
            <div class="row">
                <div class="col-md-12">
                    <hr class="mb-5">
                </div>

                <div class="col-md-3">
                    <h4>{{ __('Two Factor Authentication') }}</h4>
                </div>
                <div class="col-md-9">
                   @if (session('status') == 'two-factor-authentication-disabled')
                        <div class="alert alert-success alert-dismissible show fade">
                            {{ __('Two factor Authentication has been disabled.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('status') == 'two-factor-authentication-enabled')
                        <div class="alert alert-success alert-dismissible show fade">
                            {{ __('Two factor Authentication has been enabled.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="/user/two-factor-authentication">
                             @csrf
                                {{-- if user activate two factor authentication --}}
                                @if (auth()->user()->two_factor_secret)
                                    @method('delete')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>{{ __('Scan the following QR Code into your authentication application.') }}</p>
                                            {!! auth()->user()->twoFactorQrcodeSvg() !!}
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{ __('Save these Recovery Codes in a secure location.') }}</p>
                                            <ul>
                                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes)) as
                                                $code)
                                                <li>{{ $code }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <button class="btn btn-danger mt-3"
                                        type="submit">{{ __('Disable Two Factor Authentication') }}</button>
                                @else
                                    <button class="btn btn-primary"
                                    type="submit">{{ __('Enable Two Factor Authentication') }}</button>
                                @endif
                            </form>

                            {{-- generate recovery codes --}}
                            @if ((auth()->user()->two_factor_secret))
                                <form method="POST" action="/user/two-factor-recovery-codes">
                                    @csrf
                                    <button class="btn btn-primary mt-3 float-right" type="submit">
                                       {{ __('Regenerate Recovery Codes') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
