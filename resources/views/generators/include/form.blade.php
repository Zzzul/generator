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

    <h6 class="mt-3">{{ __('Table Field') }}</h6>

    <div class="col-md-12">
        <table class="table table-striped table-hover table-sm" id="tbl-field">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>{{ __('Field name') }}</th>
                    <th>{{ __('Data Type') }}</th>
                    <th>{{ __('Length') }}</th>
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
                            <select name="data_types[]" class="form-select data-types" required>
                                <option value="" disabled selected>--Select data type--</option>
                                @foreach (config('generator.types') as $type)
                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" option" name="select_options[]" class="form-option">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="min_lengths[]" class="form-control" min="1"
                                placeholder="Min Length">
                        </div>
                        <div class="form-group">
                            <input type="number" name="max_lengths[]" class="form-control" min="1"
                                placeholder="Max Length">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="input_types[]" class="form-select input-types" required>
                                <option value="" disabled selected>-- Select input type --</option>
                                <option value="" disabled>Select data type first</option>
                            </select>
                        </div>
                        <input type="hidden" name="mimes[]" class="form-mimes">
                        <input type="hidden" name="file_types[]" class="form-file-type">
                        <input type="hidden" name="files_sizes[]" class="form-file-size">
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="requireds[]" value="required"
                                checked />
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
                        <div class="form-group">
                            <input type="text" name="fields[]" class="form-control" placeholder="Field Name" required>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="data_types[]" class="form-select data-types" required>
                                <option value="" disabled selected>--Select type--</option>
                                ${list}
                            </select>
                            <input type="hidden" option" name="select_options[]" class="form-option">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="min_lengths[]" class="form-control" min="1"
                                placeholder="Min Length">
                        </div>
                        <div class="form-group">
                            <input type="number" name="max_lengths[]" class="form-control" min="1"
                                placeholder="Max Length">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="input_types[]" class="form-select input-types" required>
                                <option value="" disabled selected>-- Select input type --</option>
                                <option value="" disabled>Select data type first</option>
                            </select>
                        </div>
                        <input type="hidden" name="mimes[]" class="form-mimes">
                        <input type="hidden" name="file_types[]" class="form-file-type">
                        <input type="hidden" name="files_sizes[]" class="form-file-size">
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="requireds[]" value="required" checked/>
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

        $(document).on('change', '.data-types', function() {
            let index = $(this).parent().parent().parent().index()

            if ($(this).val() == 'enum') {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <div class="form-group form-option mt-2">
                    <input type="text" name="select_options[]" class="form-control" placeholder="Seperate with '|', eg: water|fire">
                </div>
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                `)
            } else if ($(this).val() == 'date') {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="date">Date</option>
                `)
            } else if ($(this).val() == 'time') {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="time">Time</option>
                `)
            } else if ($(this).val() == 'dateTime') {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="datetime-local">Datetime local</option>
                `)
            } else if (
                $(this).val() == 'text' ||
                $(this).val() == 'longText' ||
                $(this).val() == 'tinyText'
            ) {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                `)
            } else if (
                $(this).val() == 'integer' ||
                $(this).val() == 'bigInteger' ||
                $(this).val() == 'boolean' ||
                $(this).val() == 'decimal' ||
                $(this).val() == 'double' ||
                $(this).val() == 'float' ||
                $(this).val() == 'tinyInteger'
            ) {
                removeInputHidden(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="number">Number</option>
                `)
            } else {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" option" name="select_options[]" class="form-option">
                `)

                $(`.input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="text">Text</option>
                    <option value="email">Email</option>
                    <option value="file">File</option>
                `)
            }
        })

        $(document).on('change', '.input-types', function() {
            let index = $(this).parent().parent().parent().index()

            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-type`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-size`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

            if ($(this).val() == 'file') {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
                <div class="form-group mt-2 form-file">
                    <select  name="file_types[]" class="form-select file-types" required>
                        <option value="" disabled selected>-- Select file type --</option>
                        <option value="image">Image</option>
                        <option value="mimes">Mimes</option>
                    </select>
                </div>
                <div class="form-group form-file-size">
                    <input type="number" name="files_sizes[]" class="form-control" placeholder="Max size, eg: 1024" required>
                </div>
                `)
            } else {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-type`).remove()
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-size`).remove()
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="file_types[]" class="form-file-type">`
                )

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="files_sizes[]" class="form-file-size">`
                )

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="mimes[]" class="form-mimes">`
                )
            }
        })

        $(document).on('change', '.file-types', function() {
            let index = $(this).parent().parent().parent().index()

            if ($(this).val() == 'mimes') {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
                <div class="form-group mt-2 form-mimes">
                    <input type="text" name="mimes[]" class="form-control" placeholder="File type, seperate with ','. eg: pdf,docx" required>
                </div>
                `)
            } else {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="mimes[]" class="form-mimes">`
                )
            }
        })

        $(document).on('click', '.btn-delete', function() {
            let table = $('#tbl-field tbody tr')

            if (table.length > 1) {
                $(this).parent().parent().remove()
            }
        })

        function removeInputHidden(index) {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-type`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-size`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()
        }

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
