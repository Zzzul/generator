@extends('layouts.app')

@section('title', trans('Detail of Books'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Books') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Detail of book.') }}
                    </p>
                </div>

                <x-breadcrumb>
                    <li class="breadcrumb-item">
                        <a href="/">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('books.index') }}">{{ __('Books') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('Detail') }}
                    </li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <tr>
                                            <td class="fw-bold">{{ __('Title') }}</td>
                                            <td>{{ $book->title }}</td>
                                        </tr>
									<tr>
                                            <td class="fw-bold">{{ __('Price') }}</td>
                                            <td>{{ $book->price }}</td>
                                        </tr>
									<tr>
                                        <td class="fw-bold">{{ __('Cover') }}</td>
                                        <td>
                                            @if ($book->cover == null || $book->cover == 'https://i.pinimg.com/736x/73/0c/ff/730cff2088649d90e8dc2e2e254acd67.jpg')
                                            <img src="https://i.pinimg.com/736x/73/0c/ff/730cff2088649d90e8dc2e2e254acd67.jpg" alt="Cover"  class="rounded" width="200" height="150" style="object-fit: cover">
                                            @else
                                                <img src="{{ asset('storage/uploads/covers/' . $book->cover) }}" alt="Cover" class="rounded" width="200" height="150" style="object-fit: cover">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Created at') }}</td>
                                        <td>{{ $book->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Updated at') }}</td>
                                        <td>{{ $book->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
