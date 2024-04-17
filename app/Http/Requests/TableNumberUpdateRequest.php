<?php

namespace App\Http\Requests;

use App\Enums\TableStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TableNumberUpdateRequest extends FormRequest
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
            'description' => 'nullable| string',
            'status' => Rule::in([
                TableStatusEnum::SUCCESS->value,
                TableStatusEnum::PENDING->value,
            ]),
        ];
    }
}
