<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Hyperf\Context\ApplicationContext;
use Phper666\JWTAuth\Util\JWTUtil;
use Zero0719\HyperfAdmin\Model\AdminUsers;
use Zero0719\HyperfApi\Exception\BusinessException;
use Zero0719\HyperfApi\Service\TokenService;
use function Hyperf\Config\config;

class SessionsService extends BaseService
{
    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create(): array
    {
        $user = AdminUsers::where('username', $this->request->input('username'))->first();
        
        if (!$user) {
            throw new BusinessException('用户不存在');
        }

        if (!$user->passwordVerify($this->request->input('password'))) {
            throw new BusinessException('密码错误');
        }

        if (!$user->status) {
            throw new BusinessException('用户已冻结');
        }

        $tokenService = ApplicationContext::getContainer()->get(TokenService::class);

        $data = $user->only(config('admin.jwt.custom_claims'));

        return $tokenService->generate($data);
    }

    /**
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function destroy()
    {
        $tokenService = ApplicationContext::getContainer()->get(TokenService::class);

        $tokenService->destroy(JWTUtil::getToken($this->request));
    }

    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update()
    {
        $tokenService = ApplicationContext::getContainer()->get(TokenService::class);

        return $tokenService->refresh(JWTUtil::getToken($this->request));
    }

    public function me()
    {
        $user = AdminUsers::getCurrentUserInfoFromContext();

        if (in_array($user['id'], config('admin.white_list.users', [])) || array_intersect($user['roles'], config('admin.white_list.roles', []))) {
            $user['roles'][] = 'SuperAdmin';
        }

        return $user;
    }
}