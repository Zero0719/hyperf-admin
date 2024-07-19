<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Controller;

use Zero0719\HyperfAdmin\Model\AdminUsers;
use Zero0719\HyperfAdmin\Request\AdminUsersRequest;
use Zero0719\HyperfAdmin\Service\UsersService;
use Zero0719\HyperfApi\Controller\BaseController;
use Zero0719\HyperfApi\Exception\BusinessException;
use function Hyperf\Config\config;

class AdminUsersController extends BaseController
{
    /**
     * @var UsersService
     */
    protected $service;
    
    public function __construct(UsersService $service)
    {
        $this->service = $service;
    }
    
    public function list()
    {
        return $this->success($this->service->list());
    }

    public function create(AdminUsersRequest $request)
    {
        $this->service->create();

        return $this->success();
    }

    public function update(AdminUsersRequest $request)
    {
        $this->service->update();
        return $this->success();
    }
    
    public function destroy()
    {
        $this->service->destroy();
        return $this->success();
    }
    
    public function show()
    {
        return $this->success($this->service->show());
    }
}