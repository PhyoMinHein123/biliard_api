<?php

namespace App\Http\Requests;

use App\Models\TableNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Shop;
use App\Enums\OrderStatusEnum;
use App\Helpers\Enum;
use App\Models\Payment;
use App\Models\Customer;

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

        $shops = Shop::all()->pluck('id')->toArray();
        $shops = implode(',', $shops);

        $customers = Customer::all()->pluck('id')->toArray();
        $customers = implode(',', $customers);

        $payments = Payment::all()->pluck('id')->toArray();
        $payments = implode(',', $payments);

        $enum = implode(',', (new Enum(OrderStatusEnum::class))->values());

        return [
            'invoice_number' => "required|string",
            'table_number_id' => "required|in:$tableNumbers",
            'shop_id' => "required|in:$shops",
            'customer_id' => "required|in:$customers",
            'payment_id' => "required|in:$payments",
            'items' => "nullable|json",
            'checkin' => "nullable|datetime",
            'checkout' => "nullable|datetime",
            'table_charge' => "nullable|numeric",
            'items_charge' => "nullable|numeric",
            'total_time' => "nullable|string",
            'status' => "required|in:$enum",
        ];
    }
}
