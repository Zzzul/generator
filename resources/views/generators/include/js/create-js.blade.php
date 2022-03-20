<script>
    const types = {!! json_encode(config('generator.data_types')) !!}
    let selectMenu = $('#select-menu')
    let colNewMenu = $('#col-new-menu')

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

        $.ajax({
            type: 'POST',
            url: '{{ route('generators.store') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: $(this).serialize(),
            success: function(response) {
                console.log(response)

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
                console.error(xhr.responseText)

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

        if (indexHeader == 'new') {
            selectMenu.prop('disabled', true)

            selectMenu.html(
                `<option value="" disabled selected>--{{ __('Select the header first') }}--</option>
                `)

            colNewMenu.hide(300)

            colNewMenu.html(`
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="new-header">{{ __('Header') }}</label>
                            <input type="text" id="new-header" name="new_header" class="form-control"
                                placeholder="{{ __('New Header') }}" required autofocus>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id="input-new-menu">
                            <label for="new-menu">{{ __('New Menu') }}</label>
                            <input type="text" name="new_menu" id="new-menu" class="form-control"
                                placeholder="{{ __('Title') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="new-route">{{ __('Route') }}</label>
                            <input type="text" id="new-route" name="new_route" class="form-control"
                                placeholder="{{ __('New Route') }}" required>
                            <small>{{ __('If null will use the model name, ex: "/products"') }}</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="new-icon">{{ __('Icon') }}</label>
                            <input type="text" id="new-icon" name="new_icon" class="form-control"
                                placeholder="{{ __('New Icon') }}" required>
                            <small>{!! __('We recomended you to use <a href="https://icons.getbootstrap.com/" target="_blank">bootstrap icon</a>, ex: ') !!} {{ '<i class="bi bi-people"></i>' }}</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="new-submenu">{{ __('Submenu') }}</label>
                            <input type="text" id="new-submenu" name="new_submenu" class="form-control"
                                placeholder="{{ __('New Submenu') }}">
                            <small>{{ __('Optional.') }}</small>
                        </div>
                    </div>
                </div>
            `)

            colNewMenu.show(300)
        } else {
            colNewMenu.hide(300)
            colNewMenu.html('')
            selectMenu.prop('disabled', true)
            selectMenu.html('<option value="" disabled selected>Loading...</option>')

            $.ajax({
                type: 'GET',
                url: `/generators/get-sidebar-menus/${indexHeader}`,
                success: function(res) {
                    // console.log(res)

                    let options = `
                        <option value="" disabled selected>-- {{ __('Select menu') }} --</option>
                        <option value="new">{{ __('New menu') }}</option>
                    `

                    res.forEach((value, index) => {
                        options +=
                            `<option value='{"sidebar": ${indexHeader}, "menus": ${index}}'>${value.title}</option>`
                    })

                    selectMenu.html(options)
                    selectMenu.prop('disabled', false)
                    selectMenu.focus()
                },
                error: function(xhr, status, res) {
                    console.error(xhr.responseText)
                }
            })
        }
    })

    $('#select-menu').change(function() {
        let indexMenu = $(this).val()

        if (indexMenu == 'new') {
            colNewMenu.hide(300)

            colNewMenu.html(`
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" id="input-new-menu">
                            <label for="new-menu">{{ __('New Menu') }}</label>
                            <input type="text" name="new_menu" id="new-menu" class="form-control"
                                placeholder="{{ __('Title') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new-route">{{ __('Route') }}</label>
                            <input type="text" id="new-route" name="new_route" class="form-control"
                                placeholder="{{ __('New Route') }}" required>
                            <small>{{ __('If null will use the model name, ex: "/products"') }}</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new-icon">{{ __('Icon') }}</label>
                            <input type="text" id="new-icon" name="new_icon" class="form-control"
                                placeholder="{{ __('New Icon') }}" required>
                            <small>{!! __('We recomended you to use <a href="https://icons.getbootstrap.com/" target="_blank">bootstrap icon</a>, ex: ') !!} {{ '<i class="bi bi-people"></i>' }}</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new-submenu">{{ __('Submenu') }}</label>
                            <input type="text" id="new-submenu" name="new_submenu" class="form-control"
                                placeholder="{{ __('New Submenu') }}">
                            <small>{{ __('Optional.') }}</small>
                        </div>
                    </div>
                </div>
            `)

            colNewMenu.show(300)
        } else {
            colNewMenu.hide(300)
            colNewMenu.html('')
        }
    })
</script>
