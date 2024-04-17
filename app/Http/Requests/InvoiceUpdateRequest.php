<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
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

        $customers = Customer::all()->pluck('id')->toArray();
        $customers = implode(',', $customers);

        $orders = Order::all()->pluck('id')->toArray();
        $orders = implode(',', $orders);

        return [
            'customer_id' => ["in:$customers"],
            'order_id' => ["in:$orders"],
            'subtotal' => 'numeric',
            'tax' => 'nullable | numeric | max:100',
            'discount' => 'nullable | numeric | max:100',
            'total' => 'numeric',
            'payment' => 'string',
            'charge' => 'numeric',
            'refund' => 'numeric',
        ];
    }
}
