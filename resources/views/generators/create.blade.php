@extends('layouts.app')

@section('title', __('Create Module'))

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Module') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Create a new CRUD Module.') }}
                    </p>
                </div>

                <x-breadcrumb>
                    <li class="breadcrumb-item">
                        <a href="/">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('Generators') }}
                    </li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('generators.store') }}" method="POST" id="form-generator">
                                @csrf
                                @method('POST')

                                @include('generators.include.form')

                                <a href="{{ url()->previous() }}" id="btn-back" class="btn btn-secondary">{{ __('Back') }}</a>

                                <button type="submit" id="btn-save" class="btn btn-primary">{{ __('Generate') }}</button>
                            </form>
                        </div>
                    </div>

                    <div id="validation-errors" style="display: none;">
                        <div class="alert alert-danger fade show" role="alert">
                            <h4 class="alert-heading">Error</h4>
                            <ul class="mb-0"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@include('generators.include.script')
