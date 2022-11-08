<?php

namespace App\Http\Requests;

use App\Enums\GeneratorType;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreGeneratorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $columnTypes = [
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
        ];

        return [
            // regex only for string, underscores("_") and slash("/")
            'model' => ['required', 'regex:/^[A-Za-z_\/]+$/'],
            'generate_type' => ['required', new Enum(GeneratorType::class)],
            'input_types.*' => ['required'],
            'foreign_ids.*' => ['nullable'],
            'min_lengths.*' => ['nullable'],
            'max_lengths.*' => ['nullable'],
            'steps.*' => ['nullable'],
            'default_values.*' => ['nullable'],
            'fields.*' => ['required', 'regex:/^[A-Za-z_]+$/'],
            'requireds.*' => ['required', 'in:yes,no'],
            'mimes.*' => ['nullable', 'required_if:file_types.*,mimes'],
            'files_sizes.*' => ['nullable', 'required_if:input_types.*,file'],
            'select_options.*' => ['nullable', 'required_if:column_types.*,enum'],
            'constrains.*' => ['nullable', 'required_if:column_types.*,foreignId'],
            'file_types.*' => ['nullable', 'required_if:input_types.*,file', 'in:image,mimes'],
            'column_types.*' => ['required', 'in:' . implode(',', $columnTypes)],
            'on_update_foreign.*' => ['nullable'],
            'on_delete_foreign.*' => ['nullable'],
            'menu' => ['required_unless:header,new'],
            'header' => ['required'],
            'new_header' => ['required_if:header,new'],
            'new_icon' => ['required_if:header,new'],
            'new_menu' => ['required_if:header,new'],
            // 'new_route' => ['required_if:header,new'],
            'new_submenu' => ['nullable'],
        ];
    }
}
