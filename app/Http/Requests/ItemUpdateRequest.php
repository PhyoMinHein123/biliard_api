<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
        $categories = Category::all()->pluck('id')->toArray();
        $categories = implode(',', $categories);

        return [
            'name' => 'string',
            'image' => ['image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'price' => 'numeric',
            'original_price' => 'numeric',
            'status' => Rule::in([
                GeneralStatusEnum::ACTIVE->value,
                GeneralStatusEnum::DISABLE->value,
            ]),
            'category_id' => "in:$categories",
            'qty' => 'nullable',
        ];
    }
}
