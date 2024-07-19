<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Zero0719\HyperfAdmin\Model\AdminPermissions;
use Zero0719\HyperfApi\Exception\BusinessException;

class PermissionsService extends BaseService
{
    public function list()
    {
        $query = AdminPermissions::query();

        $count = $query->count();
        $lists = $this->getForPage($query);

        return [
            'lists' => $lists,
            'count' => $count
        ];
    }

    public function create()
    {
        if (!AdminPermissions::create($this->request->all())) {
            throw new BusinessException('添加失败');
        }
    }

    public function update()
    {
        $permission = AdminPermissions::findOrFail($this->request->route('id'));

        if (!$permission->update($this->request->all())) {
            throw new BusinessException('修改失败');
        }
    }

    public function show()
    {
        $permission = AdminPermissions::findOrFail($this->request->route('id'));

        return $permission->toArray();
    }

    public function destroy()
    {
        $permission = AdminPermissions::findOrFail($this->request->route('id'));

        if (!$permission->delete()) {
            throw new BusinessException('删除失败');
        }
    }
}