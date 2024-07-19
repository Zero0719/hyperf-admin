<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Zero0719\HyperfAdmin\Model\AdminRoles;
use Zero0719\HyperfApi\Exception\BusinessException;

class RolesService extends \Zero0719\HyperfAdmin\Service\BaseService
{
    public function list()
    {
        $query = AdminRoles::query();

        $query->select([
            'id', 'name', 'status', 'created_at', 'updated_at'
        ]);

        $count = $query->count();
        $lists = $this->getForPage($query);
        
        return [
            'lists' => $lists,
            'count' => $count
        ];
    }

    public function create()
    {
        if (!AdminRoles::create($this->request->all())) {
            throw new BusinessException('添加失败');
        }
    }
    
    public function update()
    {
        $role = AdminRoles::findOrFail($this->request->route('id'));
        
        if (!$role->update($this->request->all())) {
            throw new BusinessException('修改失败');
        }
    }
    
    public function show()
    {
        $role = AdminRoles::findOrFail($this->request->route('id'));
        
        return $role->toArray();
    }
    
    public function destroy()
    {
        $role = AdminRoles::findOrFail($this->request->route('id'));
        
        if (!$role->delete()) {
            throw new BusinessException('删除失败');
        }
    }
}