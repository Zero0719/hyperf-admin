<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Request;

class AdminRolesRequest extends \Hyperf\Validation\Request\FormRequest
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
             'flag' => 'required|string|max:100|' . ($id ? sprintf('unique:admin_roles,flag,%s,id', $id) : 'unique:admin_roles,flag')
        ];
    }
    
    public function attributes(): array
    {
        return [
            'name' => '角色名',
            'flag' => '角色标识'
        ];
    }
}