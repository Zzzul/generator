<div class="row mb-2">
    <div class="col-md-6">
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

    {{-- <div class="col-md-4">
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
    </div> --}}

    {{-- <hr class="my-4"> --}}

    <h6 class="mt-3">{{ __('Table Field') }}</h6>

    <div class="col-md-12">
        <table class="table table-striped table-hover table-sm" id="tbl-field">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>{{ __('Field name') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Length') }}</th>
                    <th>{{ __('Required') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <div class="form-group">
                            <input type="text" name="fields[]" class="form-control" placeholder="Field Name">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="types[]" class="form-select">
                                <option value="" disabled selected>--Select type--</option>
                                @foreach (config('generator.types') as $type)
                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="lengths[]" class="form-control" min="1" placeholder="Length">
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="requireds[]" checked />
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
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/fontawesome/all.min.css">
@endpush

@push('js')
    <script src="{{ asset('mazer') }}/vendors/jquery/jquery.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/fontawesome/all.min.js"></script>

    <script>
        const types = {!! json_encode(config('generator.types')) !!}

        $('#btn-add').click(function() {
            let table = $('#tbl-field tbody')

            let list = renderTypes()

            let tr = `
                <tr>
                    <td>${table.find('tr').length + 1}</td>
                    <td>
                        <div class="form-group m-0 p-0">
                            <input type="text" name="fields[]" class="form-control" placeholder="Field Name">
                        </div>
                    </td>
                    <td>
                        <div class="form-group m-0 p-0">
                            <select name="types[]" class="form-select select-types">
                                <option value="" disabled selected>--Select type--</option>
                                ${list}
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group m-0 p-0">
                            <input type="number" name="lengths[]" class="form-control" min="1" placeholder="Length">
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="requireds[]"  checked/>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                `

            table.append(tr)
        })

        $(document).on('change', '.select-types', function() {
            if ($(this).val() == 'enum') {
                console.log('enum - select');
            }
        })

        $(document).on('click', '.btn-delete', function() {
            let table = $('#tbl-field tbody tr')

            if (table.length > 1) {
                $(this).parent().parent().remove()
            }
        })

        function renderTypes() {
            let optionTypes = ''

            $(types).each(function(i, val) {
                optionTypes += `<option value="${val}">${capitalizeFirstLetter(val)}</option>`
            })

            return optionTypes
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    </script>
@endpush
