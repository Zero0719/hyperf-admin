<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Zero0719\HyperfAdmin\Model\AdminUsers;
use Zero0719\HyperfApi\Exception\BusinessException;
use Zero0719\HyperfAdmin\Model\AdminRoles;

class RbacService extends BaseService
{
    public function syncRoleToUser()
    {
        $roles = $this->request->input('roles', []);
        $userId = $this->request->input('userId');
        
        $user = AdminUsers::findOrFail($userId);
        
        if (!$user->roles()->sync($roles)) {
            throw new BusinessException('授予角色失败');
        }
    }
    
    public function syncPermissionToRole()
    {
        $permissions = $this->request->input('permissions', []);
        $roleId = $this->request->input('roleId');
        
        $role = AdminRoles::findOrFail($roleId);
        
        if (!$role->permissions()->sync($permissions)) {
            throw new BusinessException('授予权限失败');
        }
    }
}