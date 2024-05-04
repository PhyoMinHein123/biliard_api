<?php

namespace App\Http\Requests;

use App\Models\TableNumber;
use App\Models\Shop;
use App\Enums\OrderStatusEnum;
use App\Helpers\Enum;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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

        $shops = Shop::all()->pluck('id')->toArray();
        $shops = implode(',', $shops);

        $enum = implode(',', (new Enum(OrderStatusEnum::class))->values());

        return [
            'table_number_id' => "required|in:$tableNumbers",
            'items' => "nullable|json",
            'status' => "required|in:$enum",
            'checkin' => "nullable|datetime",
            'checkout' => "nullable|datetime",
            'table_charge' => "nullable|numeric",
            'items_charge' => "nullable|numeric",
            'total_time' => "nullable|string",
            'shop_id' => "required|in:$shops",
        ];
    }
}
