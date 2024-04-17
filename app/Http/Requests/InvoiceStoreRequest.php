<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
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
            'customer_id' => ['required', "in:$customers"],
            'order_id' => ['required', "in:$orders"],
            'subtotal' => 'required| numeric',
            'tax' => 'nullable | numeric',
            'discount' => 'nullable | numeric',
            'total' => 'required| numeric',
            'payment' => 'required| string',
            'charge' => 'required| numeric',
            'refund' => 'required| numeric',
        ];
    }
}
