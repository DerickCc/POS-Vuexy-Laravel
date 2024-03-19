<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'pic' => 'required|string|max:50',
            'address' => 'nullable|string|max:150',
            'phoneNo' => 'required|numeric|max:10000000000000000',
            'remarks' => 'nullable|string|max:150',
        ];
    }
}
