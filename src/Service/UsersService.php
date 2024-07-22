<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Zero0719\HyperfAdmin\Model\AdminUsers;
use Zero0719\HyperfApi\Exception\BusinessException;

class UsersService extends BaseService
{
    public function list()
    {
        $query = AdminUsers::query();
        
        $query->select([
            'id', 'username', 'status', 'created_at', 'updated_at'
        ]);
        
        $count = $query->count();
        
        $lists = $this->getForPage($query);
        
        return [
            'count' => $count,
            'lists' => $lists
        ];
    }

    public function create()
    {
        if ($this->checkUserByUserName($this->request->input('username'))) {
            throw new BusinessException('用户已存在');
        }

        if (!AdminUsers::create($this->request->all())) {
            throw new BusinessException('添加失败');
        }
    }

    public function checkUserByUserName(string $username)
    {
        return boolval(AdminUsers::where('username', $username)->first());
    }
    
    public function update()
    {
        $user = AdminUsers::findOrFail($this->request->route('id'));

        if ($user->username != $this->request->input('username') && $this->checkUserByUserName($this->request->input('username'))) {
            throw new BusinessException('用户名已存在');
        }
        
        if (!$user->update($this->request->all())) {
            throw new BusinessException('修改失败');
        }
    }
    
    public function destroy()
    {
        $user = AdminUsers::findOrFail($this->request->route('id'));
        
        if (!$user->delete()) {
            throw new BusinessException('删除失败');
        }
    }

    public function show()
    {
        $user = AdminUsers::select([
            'id',
            'username',
            'status',
            'created_at',
            'updated_at'
        ])->findOrFail($this->request->route('id'));

        return $user->toArray();
    }
}