<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Request;

use Hyperf\Validation\Request\FormRequest;

class AdminPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:60',
            'flag' => 'required|string|max:100|' . ($id ? sprintf('unique:admin_permissions,flag,%s,id', $id) : 'unique:admin_permissions,flag')
        ];
    }
    
    public function attributes(): array
    {
        return [
            'name' => '权限名',
            'flag' => '权限标识'
        ];
    }
}