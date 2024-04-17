<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Validation\Rule;
use App\Enums\GeneralStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class CashierStoreRequest extends FormRequest
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

        $shops = Shop::all()->pluck('id')->toArray();
        $shops = implode(',', $shops);

        return [
            'name'=>'required| string',
            'phone' => ['nullable', 'unique:cashiers,phone', 'min:9', 'max:13'],
            'shop_id' => "required| in:$shops",
            'status' => Rule::in([
                GeneralStatusEnum::ACTIVE->value,
                GeneralStatusEnum::DISABLE->value,
            ]),
        ];
    }
}
