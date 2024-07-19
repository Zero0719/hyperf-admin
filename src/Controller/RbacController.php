<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Controller;

use Zero0719\HyperfAdmin\Service\RbacService;
use Zero0719\HyperfApi\Controller\BaseController;

class RbacController extends BaseController
{
    /**
     * @var RbacService
     */
    protected $service;

    public function __construct(RbacService $service)
    {
        $this->service = $service;
    }

    public function syncRoleToUser()
    {
        $this->service->syncRoleToUser();
        return $this->success();
    }

    public function syncPermissionToRole()
    {
        $this->service->syncPermissionToRole();
        return $this->success();
    }
}