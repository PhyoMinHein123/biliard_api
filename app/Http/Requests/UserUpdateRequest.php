<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $enum = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $user = User::findOrFail(request('id'));
        $userId = $user->id;

        return [
            'name' => 'required|string| max:24 | min:4',
            'email' => 'required| email| unique:users,email,$user|string',
            'phone' => ['nullable', 'unique:users,phone,$user', 'min:9', 'max:13'],
            'password' => 'required| max:24 | min:6',
            'address' => 'string| nullable| max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'shop_id' => "required|in:$shops",
            'status' => "required|in:$enum"
        ];
    }
}
