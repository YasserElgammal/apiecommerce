<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3' ,'max:150'],
            'desc' => ['required', 'max:500'],
            'price' => ['required', 'numeric' ,'min:1'],
            'status' => ['required', 'boolean'],
            'name_ar' => ['required','min:3' ,'max:150'],
            'desc_ar' => ['required','max:500'],
        ];
    }
}
