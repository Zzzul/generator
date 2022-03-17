<div class="row mb-2">
    <div class="col-md-5">
        <div class="form-group">
            <label for="model">{{ __('Model') }}</label>
            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror"
                placeholder="{{ __('Product') }}" value="{{ old('model') }}" autofocus required>
            <small class="text-secondary">{{ __("Used ' / ' for generate a sub folder. eg: Main/Product.") }}</small>
            @error('model')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-7">
        <p class="mb-2">Generate Type</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="generate_type" id="generate-type-1" value="all" checked>
            <label class="form-check-label" for="generate-type-1">
                {{ __('All (Migration, Model, View, Controller, Route, & Request)') }}
            </label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="generate_type" id="generate-type-2"
                value="model & migration">
            <label class="form-check-label" for="generate-type-2">
                {{ __('Only Model & Migration') }}
            </label>
        </div>
    </div>

    <h6 class="mt-3">{{ __('Table Fields') }}</h6>

    <div class="col-md-12">
        <table class="table table-striped table-hover table-sm" id="tbl-field">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>{{ __('Field name') }}</th>
                    <th>{{ __('Data Type') }}</th>
                    <th width="310">{{ __('Length') }}</th>
                    <th>{{ __('Input Type') }}</th>
                    <th>{{ __('Required') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <div class="form-group">
                            <input type="text" name="fields[]" class="form-control" placeholder="Field Name" required>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="data_types[]" class="form-select form-data-types" required>
                                <option value="" disabled selected>--Select data type--</option>
                                @foreach (config('generator.data_types') as $type)
                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="select_options[]" class="form-option">
                            <input type="hidden" name="constrains[]" class="form-constrain">
                            <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" name="min_lengths[]" class="form-control form-min-lengths"
                                        min="1" placeholder="Min Length">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" name="max_lengths[]" class="form-control form-max-lengths"
                                        min="1" placeholder="Max Length">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="input_types[]" class="form-select form-input-types" required>
                                <option value="" disabled selected>-- Select input type --</option>
                                <option value="" disabled>Select data type first</option>
                            </select>
                        </div>
                        <input type="hidden" name="mimes[]" class="form-mimes">
                        <input type="hidden" name="file_types[]" class="form-file-types">
                        <input type="hidden" name="files_sizes[]" class="form-file-sizes">
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" id="required-1" type="checkbox" name="requireds[]"
                                value="yes" checked />
                            <label for="required-1">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="nullable-1" type="checkbox" name="requireds[]"
                                value="no" />
                            <label for="nullable-1">No</label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete disabled" disabled>
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h6 class="mt-3">{{ __('Sidebar Menus') }}</h6>

    <div class="col-md-6">
        <div class="form-group">
            <label for="header">{{ __('Header') }}</label>
            <select name="header" id="select-header" class="form-select" required>
                <option value="" disabled selected>-- {{ __('Select header') }} --</option>
                @foreach (config('generator.sidebars') as $keySidebar => $header)
                    <option value="{{ $keySidebar }}">{{ $header['header'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="menu">{{ __('Menu') }}</label>
            <select name="menu" id="select-menu" class="form-select" required disabled>
                <option value="" disabled selected>-- {{ __('Select header first') }} --</option>
            </select>
        </div>
    </div>
</div>
