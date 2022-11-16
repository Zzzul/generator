@extends('layouts.app')

@section('title', __('Profile'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Profile') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Change your profile information, password and enable/disable two factor authentication.') }}
                    </p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section mt-4">
            <div class="row">
                <div class="col-md-12">
                    <x-alert></x-alert>
                </div>
            </div>

            {{-- Profile --}}
            <div class="row">
                <div class="col-md-3">
                    <h4>{{ __('Profile') }}</h4>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user-profile-information.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="email">{{ __('E-mail Address') }}</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror"
                                        id="email" placeholder="{{ __('E-mail Address') }}"
                                        value="{{ old('email') ?? auth()->user()->email }}" required>

                                    @error('email', 'updateProfileInformation')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control  @error('name', 'updateProfileInformation') is-invalid @enderror"
                                        id="name" placeholder="{{ __('Name') }}"
                                        value="{{ old('name') ?? auth()->user()->name }}" required>
                                    @error('name', 'updateProfileInformation')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="avatar avatar-xl mb-3">
                                            @if (auth()->user()->avatar == null)
                                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}&s=500"
                                                    alt="Avatar">
                                            @else
                                                <img src="{{ asset('uploads/images/avatars/' . auth()->user()->avatar) }}"
                                                    alt="Avatar">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="avatar">{{ __('Avatar') }}</label>
                                            <input type="file" name="avatar"
                                                class="form-control @error('avatar', 'updateProfileInformation') is-invalid @enderror"
                                                id="avatar">

                                            @error('avatar', 'updateProfileInformation')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
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
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user-password.update') }}">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="password">{{ __('Current Password') }}</label>
                                    <input type="password" name="current_password"
                                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                        id="password" placeholder="Current Password" required>
                                    @error('current_password', 'updatePassword')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">{{ __('New Password') }}</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                        id="password" placeholder="New Password" required>
                                    @error('password', 'updatePassword')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm Password" required>
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
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="/user/two-factor-authentication">
                                @csrf
                                {{-- if user activate two factor authentication --}}
                                @if (auth()->user()->two_factor_secret)
                                    @method('delete')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>{{ __('Scan the following QR Code into your authentication application.') }}
                                            </p>
                                            {!! auth()->user()->twoFactorQrcodeSvg() !!}
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{ __('Save these Recovery Codes in a secure location.') }}</p>
                                            <ul>
                                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes)) as $code)
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
                            @if (auth()->user()->two_factor_secret)
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
