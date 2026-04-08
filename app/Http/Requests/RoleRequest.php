<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO: Add gate('edit-roles') or similar
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
'name' => [
    'required',
    'string',
    'max:255',
    'unique:roles,name' . ($this->route('role') ? ',' . $this->route('role')->id : ''),
],
            // 'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A role with this name already exists.',
            'permissions.*.exists' => 'Selected permission does not exist.',
        ];
    }
}

