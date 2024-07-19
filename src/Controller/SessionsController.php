<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Controller;

use Hyperf\Context\ApplicationContext;
use Hyperf\HttpServer\Contract\RequestInterface;
use Phper666\JWTAuth\Util\JWTUtil;
use Zero0719\HyperfAdmin\Model\AdminUsers;
use Zero0719\HyperfAdmin\Request\SessionsRequest;
use Zero0719\HyperfAdmin\Service\SessionsService;
use Zero0719\HyperfApi\Controller\BaseController;
use Zero0719\HyperfApi\Exception\BusinessException;
use Zero0719\HyperfApi\Service\TokenService;
use function Hyperf\Config\config;
use function Hyperf\Support\value;

class SessionsController extends BaseController
{
    /**
     * @var SessionsService
     */
    protected $service;
    
    public function __construct(SessionsService $service)
    {
        $this->service = $service;
    }
    
    public function create(SessionsRequest $request)
    {
        return $this->success($this->service->create());
    }

    public function destroy()
    {
        $this->service->destroy();
        return $this->success();
    }

    public function update(RequestInterface $request)
    {
        return $this->success($this->service->update());
    }
    
    public function me()
    {
        return $this->success($this->service->me());
    }
}