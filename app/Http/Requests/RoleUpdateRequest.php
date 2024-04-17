<?php

namespace App\Http\Requests;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
        $role = Role::findOrFail(request('id'));
        $roleId = $role->id;

        $permissions = Permission::all()->pluck('id')->toArray();
        $permissions = implode(',', $permissions);

        return [
            'name' => "string | unique:roles,name,$roleId",
            'description' => 'string | nullable',
            'permissions' => 'array',
            'permissions.*' => "required| numeric | in:$permissions",
        ];

    }
}
