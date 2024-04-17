<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusEnum;
use App\Models\TableNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
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
        $tableNumbers = TableNumber::all()->pluck('id')->toArray();
        $tableNumbers = implode(',', $tableNumbers);

        return [
            'table_number_id' => "in:$tableNumbers",
            'status' => Rule::in([
                OrderStatusEnum::PENDING->value,
                OrderStatusEnum::SUCCESS->value,
            ]),
            'guest' => 'numeric',
            'checkin' => 'date_format:Y-m-d H:i:s',
        ];
    }
}
