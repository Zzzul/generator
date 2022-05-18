<script>
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
            dataType == 'char'
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

    function removeAllInputHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-constrain`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-foreign-id`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-on-update-foreign`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-on-delete-foreign`).remove()

        removeInputTypeHidden(index)
    }

    function removeInputTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-types`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-file-sizes`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()
    }

    function addInputTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
            `<input type="hidden" name="file_types[]" class="form-file-types">
            <input type="hidden" name="files_sizes[]" class="form-file-sizes">
            <input type="hidden" name="mimes[]" class="form-mimes">`
        )
    }

    function addDataTypeHidden(index) {
        $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
            <input type="hidden" name="select_options[]" class="form-option">
            <input type="hidden" name="constrains[]" class="form-constrain">
            <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
            <input type="hidden" name="on_update_foreign[]" class="form-on-update-foreign">
            <input type="hidden" name="on_delete_foreign[]" class="form-on-delete-foreign">
        `)
    }

    function getColumnTypes() {
        let listDataTypes = {!! json_encode(config('generator.column_types')) !!}
        let optionTypes = ''

        $(listDataTypes).each(function(i, val) {
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
            if(i < 1){
                $(`.btn-delete:eq(${i})`).prop('disabled', true)
            }else{
                $(`.btn-delete:eq(${i})`).prop('disabled', false)
            }
            no++
        })
    }

    function setModelName(string) {
        if (string != '') {
            let split = string.split("/")

            if (split.length > 1) {
                return convertToPlural(split[split.length - 1])
            } else {
                return convertToPlural(string)
            }
        } else {
            return ''
        }
    }

    function setNewHeaderName(string) {
        if (string != '') {
            let split = string.split("/")

            if (split.length > 1) {
                return capitalizeFirstLetter(convertToPlural(split[0]))
            } else {
                return capitalizeFirstLetter(convertToPlural(string))
            }
        } else {
            return ''
        }
    }

    function convertToPlural(string) {
        if (string != '') {

            let lastChar = string.substr(string.length - 1)

            if (lastChar == 'y') {
                return `${string.substr(0, string.length - 1)}ies`
            } else {
                return `${string}s`
            }
        } else {
            return ''
        }
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
