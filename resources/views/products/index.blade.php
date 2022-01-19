@extends('layouts.app')

@section('title', trans('Products'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Products') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Below is a list of all products.') }}
                    </p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Products') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            {{--    @can('create product') --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i>
                        {{ __('Create a new product') }}
                    </a>
                </div>
            {{--   @endcan --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive p-1">
                                <table class="table table-striped" id="data-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
											<th>{{ __('Price') }}</th>
											<th>{{ __('Currency') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Updated At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/jquery-datatables/jquery.dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/fontawesome/all.min.css">
@endpush

@push('js')
    <script src="{{ asset('mazer') }}/vendors/jquery/jquery.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/jquery-datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/jquery-datatables/custom.jquery.dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/fontawesome/all.min.js"></script>
    <script>
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
				{
                    data: 'price',
                    name: 'price'
                },
				{
                    data: 'currency',
                    name: 'currency'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });
    </script>
@endpush
