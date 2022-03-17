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
            removeAllInputHidden(index)
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
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="date">Date</option>
            `)
        } else if ($(this).val() == 'time') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="time">Time</option>
            `)
        } else if ($(this).val() == 'dateTime') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="datetime-local">Datetime local</option>
            `)
        } else if ($(this).val() == 'foreignId') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <input type="hidden" name="select_options[]" class="form-option">
            `)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
            <div class="form-group form-constrain mt-2">
                <input type="text" name="constrains[]" class="form-control" placeholder="Constrain / model name" required>
                <small class="text-secondary">Used '/' if related model at sub folder, eg: Main/Product.</small>
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
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

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
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="number">Number</option>
            `)
        } else if ($(this).val() == 'boolean') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="select">Select</option>
                <option value="radio">Radio</option>
            `)
        } else {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addDataTypeHidden(index)

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
        let minLength = $(`.form-min-lengths:eq(${index})`)
        let maxLength = $(`.form-max-lengths:eq(${index})`)

        removeInputTypeHidden(index)

        if ($(this).val() == 'file') {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')

            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
            <div class="form-group mt-2 form-file-types">
                <select name="file_types[]" class="form-select" required>
                    <option value="" disabled selected>-- Select file type --</option>
                    <option value="image">Image</option>
                </select>
            </div>
            <div class="form-group form-file-sizes">
                <input type="number" name="files_sizes[]" class="form-control" placeholder="Max size(kb), eg: 1024" required>
            </div>
            `)
        } else if ($(this).val() == 'email') {

            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')

            addInputTypeHidden(index)
        } else if ($(this).val() == 'text') {

            minLength.prop('readonly', false)
            maxLength.prop('readonly', false)

            addInputTypeHidden(index)
        } else {

            addInputTypeHidden(index)
        }
    })

    $(document).on('change', '.file-types', function() {
        let index = $(this).parent().parent().parent().index()

        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

        if ($(this).val() == 'mimes') {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
            <div class="form-group mt-2 form-mimes">
                <input type="text" name="mimes[]" class="form-control" placeholder="File type, seperate with ','. eg: pdf,docx" required>
            </div>
            `)
        } else {
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

    $('#form-generator').submit(function(e) {
        e.preventDefault()

        const btnBack = $('#btn-back')
        const btnSave = $('#btn-save')
        const btnAdd = $('#btn-add')

        btnBack.prop('disabled', true)
        btnSave.prop('disabled', true)
        btnAdd.prop('disabled', true)

        btnBack.text('Loading...')
        btnSave.text('Loading...')
        btnAdd.text('Loading...')

        let modules = {
            model: $('#model').val(),
            generate_type: $('input[name="generate_type"]').val(),
            menu: $('[name="menu"]').val(),
            fields: $('input[name="fields[]"]').map(function() {
                return $(this).val()
            }).get(),
            data_types: $('select[name="data_types[]"]').map(function() {
                return $(this).val()
            }).get(),
            select_options: $('input[name="select_options[]"]').map(function() {
                return $(this).val()
            }).get(),
            constrains: $('input[name="constrains[]"]').map(function() {
                return $(this).val()
            }).get(),
            foreign_ids: $('input[name="foreign_ids[]"]').map(function() {
                return $(this).val()
            }).get(),
            min_lengths: $('input[name="min_lengths[]"]').map(function() {
                return $(this).val()
            }).get(),
            max_lengths: $('input[name="max_lengths[]"]').map(function() {
                return $(this).val()
            }).get(),
            input_types: $('select[name="input_types[]"]').map(function() {
                return $(this).val()
            }).get(),
            files_sizes: $('[name="files_sizes[]"]').map(function() {
                return $(this).val()
            }).get(),
            file_types: $('[name="file_types[]"]').map(function(i) {
                return $(this).val()
            }).get(),
            mimes: $('input[name="mimes[]"]').map(function() {
                return $(this).val()
            }).get(),
            requireds: $('.form-check-input:checkbox:checked').map(function() {
                return $(this).val()
            }).get()
        }

        $.ajax({
            type: 'POST',
            url: '{{ route('generators.store') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: modules,
            success: function(response) {
                // console.log(response)
                $('#validation-errors').hide()

                Swal.fire({
                    icon: 'success',
                    title: 'Module generated successfully!',
                    text: 'Success'
                }).then(function() {
                    window.location = '{{ route('generators.create') }}'
                })
            },
            error: function(xhr, status, response) {
                // console.error(xhr.responseText)

                let validationErrors = $('#validation-errors')
                let validationUl = $('#validation-errors .alert-danger ul')

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                })

                validationUl.html('')
                $.each(xhr.responseJSON.errors, function(key, value) {
                    if (Array.isArray(value)) {
                        value.forEach((v, i) => {
                            validationUl.append(`<li class="m-0 p-0">${v}</li>`)
                        })
                    } else {
                        validationUl.append(`<li class="m-0 p-0">${value}</li>`)
                    }
                })
                $('#validation-errors').show()

                btnBack.prop('disabled', false)
                btnSave.prop('disabled', false)
                btnAdd.prop('disabled', false)

                btnBack.text('Back')
                btnSave.text('Generate')
                btnAdd.text('Add')
            }
        })
    })

    $('#select-header').change(function() {
        let indexHeader = $(this).val()

        $('#select-menu').prop('disabled', true)
        $('#select-menu').html('<option value="" disabled selected>Loading...</option>')

        $.ajax({
            type: 'GET',
            url: `/generators/get-sidebar-menus/${indexHeader}`,
            success: function(res) {
                // console.log(res)

                let options =
                    '<option value="" disabled selected>-- {{ __('Select menu') }} --</option>'
                res.forEach((value, index) => {
                    options +=
                        `<option value='{"sidebar": ${indexHeader}, "menus": ${index}}'>${value.title}</option>`
                })

                // console.log(options)

                $('#select-menu').html(options)
                $('#select-menu').prop('disabled', false)
            },
            error: function(xhr, status, res) {
                // console.error(xhr.responseText)
            }
        })
    })
</script>
