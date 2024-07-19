<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Controller;

use Zero0719\HyperfAdmin\Request\AdminRolesRequest;
use Zero0719\HyperfApi\Controller\BaseController;
use Zero0719\HyperfAdmin\Service\RolesService;

class AdminRolesController extends BaseController
{
    /**
     * @var RolesService
     */
    protected $service;

    public function __construct(RolesService $service)
    {
        $this->service = $service;
    }

    public function list()
    {
        return $this->success($this->service->list());
    }
    
    public function create(AdminRolesRequest $request)
    {
        $this->service->create();

        return $this->success();
    }
    
    public function update(AdminRolesRequest $request)
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