<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopUpdateRequest extends FormRequest
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
            'name' => 'string',
            'phone' => ['nullable', 'min:9', 'max:13'],
            'address' => 'string',
            'open_time' => 'date_format:H:i',
            'close_time' => ['date_format:H:i', 'after:open_time'],
        ];
    }
}
