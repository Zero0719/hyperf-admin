<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Controller;

use Zero0719\HyperfAdmin\Request\AdminPermissionsRequest;
use Zero0719\HyperfApi\Controller\BaseController;
use Zero0719\HyperfAdmin\Service\PermissionsService;

class AdminPermissionsController extends BaseController
{
    /**
     * @var PermissionsService
     */
    public $service;
    
    public function __construct(PermissionsService $service)
    {
        $this->service = $service;
    }
    
    public function list()
    {
        return $this->success($this->service->list());
    }

    public function create(AdminPermissionsRequest $request)
    {
        $this->service->create();
        return $this->success();
    }

    public function update(AdminPermissionsRequest $request)
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