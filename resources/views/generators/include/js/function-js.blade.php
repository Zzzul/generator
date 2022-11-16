<script>
    function checkMinAndMaxLength(index) {
        let columType = $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-column-types`).val()
        let minLength = $(`.form-min-lengths:eq(${index})`)
        let maxLength = $(`.form-max-lengths:eq(${index})`)

        if (
            columType == 'string' ||
            columType == 'text' ||
            columType == 'longText' ||
            columType == 'tinyText' ||
            columType == 'varchar' ||
            columType == 'char'
        ) {
            minLength.prop('readonly', false)
            maxLength.prop('readonly', false)
        } else {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')
        }

        console.log("column type: ", columType)
    }

    function removeAllInputHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-constrain`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-foreign-id`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-on-update-foreign`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-on-delete-foreign`).remove()

        // $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .form-default-value`).remove()

        removeInputTypeHidden(index)
    }

    function removeInputTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-types`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-sizes`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-step`).remove()
    }

    function addInputTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
            `<input type="hidden" name="file_types[]" class="form-file-types">
            <input type="hidden" name="files_sizes[]" class="form-file-sizes">
            <input type="hidden" name="mimes[]" class="form-mimes">
            <input type="hidden" name="steps[]" class="form-step">`
        )
    }

    function addColumTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
            <input type="hidden" name="select_options[]" class="form-option">
            <input type="hidden" name="constrains[]" class="form-constrain">
            <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
            <input type="hidden" name="on_update_foreign[]" class="form-on-update-foreign">
            <input type="hidden" name="on_delete_foreign[]" class="form-on-delete-foreign">
        `)

        // $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`<input type="hidden" name="default_values[]" class="form-default-value">`)
    }

    function getColumnTypes() {
        let listColumTypes = [
            'string',
            'integer',
            'text',
            'bigInteger',
            'boolean',
            'char',
            'date',
            'time',
            'year',
            'dateTime',
            'decimal',
            'double',
            'enum',
            'float',
            'foreignId',
            'tinyInteger',
            'mediumInteger',
            'tinyText',
            'mediumText',
            'longText'
        ]

        let optionTypes = ''

        $(listColumTypes).each(function(i, val) {
            optionTypes += `<option value="${val}">${capitalizeFirstLetter(val)}</option>`
        })

        return optionTypes
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    function generateNo() {
        let no = 1

        $('#tbl-field tbody tr').each(function(i) {
            $(this).find('td:nth-child(1)').html(no)
            if (i < 1) {
                $(`.btn-delete:eq(${i})`).prop('disabled', true)
            } else {
                $(`.btn-delete:eq(${i})`).prop('disabled', false)
            }
            no++
        })
    }

    function setModelName(string) {
        if (string != '') {
            newString = string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ' ');
            let split = newString.split("/")

            if (split.length > 1) {
                return convertToPlural(split[split.length - 1])
            } else {
                return convertToPlural(newString)
            }
        } else {
            return ''
        }
    }

    function setNewHeaderName(string) {
        if (string != '') {
            newString = string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ' ');
            let split = string.split("/")

            if (split.length > 1) {
                return capitalizeFirstLetter(convertToPlural(split[0]))
            } else {
                return capitalizeFirstLetter(convertToPlural(newString))
            }
        } else {
            return ''
        }
    }

    function convertToPlural(string) {
        if (string != '') {

            let lastChar = string.substr(string.length - 1)

            switch (lastChar) {
                case 'y':
                    return `${string.substr(0, string.length - 1)}ies`
                    break;
                case 's':
                    return `${string}`
                    break;
                default:
                    return `${string}s`
                    break;
            }
        } else {
            return ''
        }
    }

    function setInputTypeDefaultValue(index) {
        let checkColumnType = $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-column-types`).val()
        let checkInputType = $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-input-types`).val()

        if (
            checkColumnType == 'integer' ||
            checkColumnType == 'bigInteger' ||
            checkColumnType == 'boolean' ||
            checkColumnType == 'decimal' ||
            checkColumnType == 'double' ||
            checkColumnType == 'float' ||
            checkColumnType == 'range' ||
            checkColumnType == 'year' ||
            checkColumnType == 'tinyInteger'
        ) {
            return 'number'
        }

        if (
            checkInputType == 'text' ||
            checkInputType == 'textarea' ||
            checkInputType == 'file' ||
            checkInputType == 'hidden' ||
            checkInputType == 'no-input'
        ) {
            return 'text'
        }

        return checkInputType
    }

    let rowDrag

    function dragStart() {
        rowDrag = event.target
    }

    function dragOver() {
        event.preventDefault()

        let children = Array.from(event.target.parentNode.parentNode.children)

        if (children.indexOf(event.target.parentNode) > children.indexOf(rowDrag)) {
            event.target.parentNode.after(rowDrag)
        } else {
            event.target.parentNode.before(rowDrag)
        }

        generateNo()
    }
</script>
