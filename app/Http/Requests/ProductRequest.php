<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:2048',
            'stock' => 'nullable|numeric',
            'uom' => 'required|string|max:50',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'remarks' => 'nullable|string|max:150'
        ];
    }

    protected function prepareForValidation()
    {
        // Modify purchase_price and selling_price before validation
        $this->merge([
            'purchase_price' => $this->formatPrice($this->input('purchase_price')),
            'selling_price' => $this->formatPrice($this->input('selling_price'))
        ]);
    }

    protected function formatPrice(string $price)
    {
        return str_replace(',', '', $price);
    }
}
