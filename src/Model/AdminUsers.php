<?php

declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Model;

use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\DbConnection\Model\Model;
use Hyperf\HttpServer\Contract\RequestInterface;
use Phper666\JWTAuth\Util\JWTUtil;
use Zero0719\HyperfApi\Exception\BusinessException;
use function Hyperf\Config\config;
use Zero0719\HyperfApi\Service\TokenService;

/**
 */
class AdminUsers extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'username', 'password'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $this->encry($value);
    }

    public function encry(string $password = '')
    {
        return password_hash($password . $this->getSalt(), PASSWORD_DEFAULT);
    }
    
    public function passwordVerify(string $password = ''): bool
    {
        return password_verify($password . $this->getSalt(), $this->password);;
    }
    
    private function getSalt()
    {
        return config('admin.password_salt');
    }
    
    public function roles()
    {
        return $this->belongsToMany(AdminRoles::class, 'admin_users_roles', 'user_id', 'role_id');
    }
    
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }
    
    public function hasPermission($permission): bool
    {
        return in_array($permission, $this->permissions()->pluck('flag')->toArray());
    }
    
    public static function getCurrentUserInfoFromContext()
    {
        if (!Context::has('admin_user')) {
            // 从 token 中解析一次，查询用户信息并记录当前上下文
            $request = ApplicationContext::getContainer()->get(RequestInterface::class);
            $customClaims = JWTUtil::getParserData($request);
            $user = AdminUsers::find($customClaims['id']);
            if (!$user) {
                throw new BusinessException('用户不存在');
            }
            
            $contextData = $user->only('id', 'username', 'status');
            
            $contextData['roles'] = $user->roles->pluck('flag')->toArray();
            $contextData['permissions'] = $user->permissions()->pluck('flag')->toArray();
            Context::set('admin_user', $contextData);
            
            return $contextData;
        }
        
        return Context::get('admin_user');
    }
}
