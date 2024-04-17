<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required| string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'price' => 'required| numeric',
            'original_price' => 'required| numeric',
            'category_id' => "required| in:$categories",
            'qty' => 'nullable',
        ];
    }
}
