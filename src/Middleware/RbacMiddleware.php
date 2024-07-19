<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Middleware;

use Hyperf\Context\Context;
use Phper666\JWTAuth\Util\JWTUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zero0719\HyperfApi\Exception\BusinessException;
use Zero0719\HyperfApi\Utils\CommonUtil;
use Zero0719\HyperfAdmin\Model\AdminUsers;
use function Hyperf\Config\config;

class RbacMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = AdminUsers::getCurrentUserInfoFromContext();

        // 白名单用户直接放行
        if (in_array($user['id'], config('admin.white_list.users'))) {
            return $handler->handle($request);
        }

        // 白名单角色直接放行
        foreach ($user['roles'] as $role) {
            if (in_array($role, config('admin.white_list.roles'))) {
                return $handler->handle($request);
            }
        }

        // 没有路由命名统一拒绝
        $routeName = CommonUtil::getCurrentRouteName();
        if (!$routeName) {
            throw new BusinessException('路由无命名，拒绝通过');
        }

        if (!in_array($routeName, $user['permissions'])) {
            throw new BusinessException('权限不足');
        }

        return $handler->handle($request);
    }
}