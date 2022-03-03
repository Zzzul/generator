<?php

namespace App\Http\Requests;

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
        return [
            // regex only for string, underscores("_") and slash("/")
            'model' => 'required|regex:/^[A-Za-z_\/]+$/',
            'generate_type' => 'required|in:all,model & migration',
            'input_types.*' => 'required',
            'foreign_ids.*' => 'nullable',
            'min_lengths.*' => 'nullable',
            'max_lengths.*' => 'nullable',
            'fields.*' => 'required|alpha_dash',
            'requireds.*' => 'required|in:yes,no',
            'mimes.*' => 'nullable|required_if:file_types.*,mimes',
            'files_sizes.*' => 'nullable|required_if:input_types.*,file',
            'select_options.*' => 'nullable|required_if:data_types.*,enum',
            'constrains.*' => 'nullable|required_if:data_types.*,foreignId',
            'file_types.*' => 'nullable|required_if:input_types.*,file|in:image,mimes',
            'data_types.*' => 'required|in:' . implode(',', config('generator.data_types')),
        ];
    }
}