<div class="row mb-2">
    <div class="col-md-5">
        <div class="form-group">
            <label for="model">{{ __('Model') }}</label>
            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror"
                placeholder="{{ __('Product') }}" value="{{ old('model') }}" autofocus required>
            <small class="text-secondary">{{ __("Used ' / ' for generate a sub folder. eg: Master/Product.") }}</small>
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
                {{ __('All (Model, Migration, Controller, View, Route, & Request)') }}
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

    <h6 class="mt-3">{{ __('Table Field') }}</h6>

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
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/fontawesome/all.min.css">
@endpush

@push('js')
    <script src="{{ asset('mazer') }}/vendors/jquery/jquery.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/fontawesome/all.min.js"></script>

    <script>
        const types = {!! json_encode(config('generator.data_types')) !!}

        $('#btn-add').click(function() {
            let table = $('#tbl-field tbody')

            let list = renderTypes()
            let no = table.find('tr').length + 1
            let tr = `
                <tr>
                    <td>${no}</td>
                    <td>
                        <div class="form-group">
                            <input type="text" name="fields[]" class="form-control" placeholder="Field Name" required>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select name="data_types[]" class="form-select form-data-types" required>
                                <option value="" disabled selected>--Select type--</option>
                                ${list}
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
                                    <input type="number" name="min_lengths[]" class="form-control form-min-lengths" min="1"
                                        placeholder="Min Length">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" name="max_lengths[]" class="form-control form-max-lengths" min="1"
                                        placeholder="Max Length">
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
                            <input class="form-check-input" id="required-${no}" type="checkbox" name="requireds[]"
                                value="yes" checked />
                            <label for="required-${no}">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="nullable-${no}" type="checkbox" name="requireds[]"
                                value="no" />
                            <label for="nullable-${no}">No</label>
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

        $(document).on('change', '.form-data-types', function() {
            let index = $(this).parent().parent().parent().index()

            if ($(this).val() == 'enum') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <div class="form-group form-option mt-2">
                    <input type="text" name="select_options[]" class="form-control" placeholder="Seperate with '|', eg: water|fire">
                </div>
                <input type="hidden" name="constrains[]" class="form-constrain">
                <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                `)
            } else if ($(this).val() == 'date') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="date">Date</option>
                `)
            } else if ($(this).val() == 'time') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="time">Time</option>
                `)
            } else if ($(this).val() == 'dateTime') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="datetime-local">Datetime local</option>
                `)
            } else if ($(this).val() == 'foreignId') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                `)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <div class="form-group form-constrain mt-2">
                    <input type="text" name="constrains[]" class="form-control" placeholder="Constrain / model name" required>
                    <small class="text-secondary">Used '/' if related model at sub folder, eg: Master/Product.</small>
                </div>
                <div class="form-group form-foreign-id mt-2">
                    <input type="text" name="foreign_ids[]" class="form-control" placeholder="Foreign key (optional)">
                </div>
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="select">Select</option>
                `)
            } else if (
                $(this).val() == 'text' ||
                $(this).val() == 'longText' ||
                $(this).val() == 'tinyText'
            ) {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                `)
            } else if (
                $(this).val() == 'integer' ||
                $(this).val() == 'bigInteger' ||
                $(this).val() == 'decimal' ||
                $(this).val() == 'double' ||
                $(this).val() == 'float' ||
                $(this).val() == 'tinyInteger'
            ) {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="number">Number</option>
                `)
            } else if ($(this).val() == 'boolean') {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                `)
            } else {
                removeInputHidden(index)
                checkMinAndMaxLength(index)

                $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                    <input type="hidden" name="select_options[]" class="form-option">
                    <input type="hidden" name="constrains[]" class="form-constrain">
                    <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                `)

                $(`.form-input-types:eq(${index})`).html(`
                    <option value="" disabled selected>-- Select input type --</option>
                    <option value="text">Text</option>
                    <option value="email">Email</option>
                    <option value="file">File</option>
                `)
            }
        })

        $(document).on('change', '.form-input-types', function() {
            let index = $(this).parent().parent().parent().index()

            $(`.form-file-types:eq(${index})`).remove()
            $(`.form-file-sizes:eq(${index})`).remove()
            $(`.form-mimes:eq(${index})`).remove()

            if ($(this).val() == 'file') {
                // <option value="mimes">Mimes</option>

                let minLength = $(`.form-min-lengths:eq(${index})`)
                let maxLength = $(`.form-max-lengths:eq(${index})`)

                minLength.prop('readonly', true)
                maxLength.prop('readonly', true)
                minLength.val('')
                maxLength.val('')

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
                <div class="form-group mt-2 form-file-types">
                    <select  name="file_types[]" class="form-select file-types" required>
                        <option value="" disabled selected>-- Select file type --</option>
                        <option value="image">Image</option>
                    </select>
                </div>
                <div class="form-group form-file-sizes">
                    <input type="number" name="files_sizes[]" class="form-control" placeholder="Max size(kb), eg: 1024" required>
                </div>
                `)
            } else if ($(this).val() == 'email') {
                let minLength = $(`.form-min-lengths:eq(${index})`)
                let maxLength = $(`.form-max-lengths:eq(${index})`)

                minLength.prop('readonly', true)
                maxLength.prop('readonly', true)
                minLength.val('')
                maxLength.val('')
            } else if ($(this).val() == 'text') {
                let minLength = $(`.form-min-lengths:eq(${index})`)
                let maxLength = $(`.form-max-lengths:eq(${index})`)

                minLength.prop('readonly', false)
                maxLength.prop('readonly', false)
            } else {
                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="file_types[]" class="form-file-types">`
                )

                $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                    `<input type="hidden" name="files_sizes[]" class="form-file-sizes">`
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

        $(document).on('change', 'input[type="checkbox"]', function() {
            let index = $(this).parent().parent().parent().index()

            if ($(this).val() == 'yes') {
                $(`#required-${index + 1}`).prop('checked', true)
                $(`#nullable-${index + 1}`).prop('checked', false)
            } else if ($(this).val() == 'no') {
                $(`#nullable-${index + 1}`).prop('checked', true)
                $(`#required-${index + 1}`).prop('checked', false)
            }
        })

        $(document).on('click', '.btn-delete', function() {
            let table = $('#tbl-field tbody tr')

            if (table.length > 1) {
                $(this).parent().parent().remove()
                generateNo()
            }
        })

        function checkMinAndMaxLength(index) {
            let dataType = $(`.form-data-types:eq(${index})`).val()
            let minLength = $(`.form-min-lengths:eq(${index})`)
            let maxLength = $(`.form-max-lengths:eq(${index})`)

            if (
                dataType == 'string' ||
                dataType == 'text' ||
                dataType == 'longText' ||
                dataType == 'tinyText' ||
                dataType == 'varchar' ||
                dataType == 'char' ||
                dataType == 'float'
            ) {
                minLength.prop('readonly', false)
                maxLength.prop('readonly', false)
            } else {
                minLength.prop('readonly', true)
                maxLength.prop('readonly', true)
                minLength.val('')
                maxLength.val('')
            }
        }

        function removeInputHidden(index) {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-constrain`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-foreign-id`).remove()

            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-types`).remove()
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-sizes`).remove()
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

        function generateNo() {
            let no = 1

            $('#tbl-field tbody tr').each(function() {
                $(this).find('td:nth-child(1)').html(no)
                no++
            })
        }
    </script>
@endpush
