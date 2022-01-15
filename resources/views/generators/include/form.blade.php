<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group">
            <label for="model">{{ __('Model') }}</label>
            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror"
                placeholder="{{ __('Model Name') }}" value="{{ old('model') }}" autofocus required>
            @error('model')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="table">{{ __('Table') }}</label>
            <input type="text" name="table" id="table" class="form-control @error('table') is-invalid @enderror"
                placeholder="{{ __('Table Name') }}" value="{{ old('table') }}" required>
            @error('table')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="controller">{{ __('Controller') }}</label>
            <input type="text" name="controller" id="controller"
                class="form-control @error('controller') is-invalid @enderror"
                placeholder="{{ __('Controller Name') }}" value="{{ old('controller') }}" required>
            @error('controller')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <hr class="my-4">

    {{-- <h6>{{ __('Table Field') }}</h6> --}}

    <div class="col-md-3">
        <div class="form-group">
            <label for="field">{{ __('Field') }}</label>
            <input type="text" name="field" id="field" class="form-control @error('field') is-invalid @enderror"
                placeholder="{{ __('Field Name') }}" value="{{ old('field') }}">
            @error('field')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="type">{{ __('Type') }}</label>
            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                <option value="" disabled selected>{{ __('--Select type--') }}</option>
                @foreach (config('generator.types') as $type)
                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="length">{{ __('Length') }}</label>
            <input type="number" name="length" id="length" class="form-control @error('length') is-invalid @enderror"
                min="1" placeholder="{{ __('Length') }}" value="{{ old('length') }}">
            @error('length')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-check mt-4">
            <label class="form-check-label" for="nullable">Nullable</label>
            <input class="form-check-input" type="checkbox" id="nullable" name="nullable" value="yes" />
        </div>
    </div>

    <div class="col-md-2">
        <label></label>
        <button class="btn btn-primary btn-block" id="btn-add">{{ __('Add') }}</button>
        <button class="btn btn-info btn-block" id="btn-update" style="display: none;">{{ __('Update') }}</button>

    </div>

    <div class="col-md-12">
        <table class="table table-striped table-hover table-sm mt-3" id="table-field">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Field name') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Length') }}</th>
                    <th>{{ __('Nullable') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        name
                        <input type="hidden" name="fields[]" value="name">
                    </td>
                    <td>
                        string
                        <input type="hidden" name="types[]" value="string">
                    </td>
                    <td>
                        30
                        <input type="hidden" name="lengths[]" value="30">
                    </td>
                    <td>
                        <i class="fas fa-check"></i>
                        <input type="hidden" name="nullables[]" value="yes">
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm btn-edit m-0">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm btn-delete m-0">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        stock
                        <input type="hidden" name="fields[]" value="stock">
                    </td>
                    <td>
                        integer
                        <input type="hidden" name="types[]" value="integer">
                    </td>
                    <td>
                        -
                        <input type="hidden" name="lengths[]" value="">
                    </td>
                    <td>
                        <i class="fas fa-times"></i>
                        <input type="hidden" name="nullables[]" value="no">
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm btn-edit m-0">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm btn-delete m-0">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/fontawesome/all.min.css">
@endpush

@push('js')
    <script src="{{ asset('mazer') }}/vendors/jquery/jquery.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/fontawesome/all.min.js"></script>
@endpush
