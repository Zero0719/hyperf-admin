<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Command;

use FastRoute\Route;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\DbConnection\Db;
use Hyperf\Server\ServerFactory;
use Zero0719\HyperfAdmin\Model\AdminPermissions;
use Zero0719\HyperfAdmin\Model\AdminRoles;
use Zero0719\HyperfAdmin\Model\AdminUsers;
use function Hyperf\Support\make;

#[Command]
class AdminInstallCommand extends HyperfCommand
{
    protected ?string $name = 'admin:install';

    private $routeString = <<<EOT
/*---admin route begin---*/
Router::addGroup('/admin', function () {
    Router::post('/sessions', [Zero0719\HyperfAdmin\Controller\SessionsController::class, 'create'], [
        'name' => 'login'
    ]);

    Router::addGroup('', function () {
        Router::delete('/sessions', [Zero0719\HyperfAdmin\Controller\SessionsController::class, 'destroy']);
        Router::put('/sessions', [Zero0719\HyperfAdmin\Controller\SessionsController::class, 'update']);

        Router::get('/users/{id}', [Zero0719\HyperfAdmin\Controller\AdminUsersController::class, 'show']);
        Router::get('/roles/{id}', [Zero0719\HyperfAdmin\Controller\AdminRolesController::class, 'show']);
        Router::get('/permissions/{id}', [Zero0719\HyperfAdmin\Controller\AdminPermissionsController::class, 'show']);

        // 需要rbac鉴权部分
        Router::addGroup('', function () {
            Router::get('/users', [Zero0719\HyperfAdmin\Controller\AdminUsersController::class, 'list'], ['name' => 'userList']);
            Router::post('/users', [Zero0719\HyperfAdmin\Controller\AdminUsersController::class, 'create'], ['name' => 'userCreate']);
            Router::put('/users/{id}', [Zero0719\HyperfAdmin\Controller\AdminUsersController::class, 'update'], ['name' => 'userUpdate']);
            Router::delete('/users/{id}', [Zero0719\HyperfAdmin\Controller\AdminUsersController::class, 'destroy'], ['name' => 'userDestroy']);

            Router::get('/roles', [Zero0719\HyperfAdmin\Controller\AdminRolesController::class, 'list'], ['name' => 'roleList']);
            Router::post('/roles', [Zero0719\HyperfAdmin\Controller\AdminRolesController::class, 'create'], ['name' => 'roleCreate']);
            Router::put('/roles/{id}', [Zero0719\HyperfAdmin\Controller\AdminRolesController::class, 'update'], ['name' => 'roleUpdate']);
            Router::delete('/roles/{id}', [Zero0719\HyperfAdmin\Controller\AdminRolesController::class, 'destroy'], ['name' => 'roleDestroy']);

            Router::get('/permissions', [Zero0719\HyperfAdmin\Controller\AdminPermissionsController::class, 'list'], ['name' => 'permissionList']);
            Router::post('/permissions', [Zero0719\HyperfAdmin\Controller\AdminPermissionsController::class, 'create'], ['name' => 'permissionCreate']);
            Router::put('/permissions/{id}', [Zero0719\HyperfAdmin\Controller\AdminPermissionsController::class, 'update'], ['name' => 'permissionUpdate']);
            Router::delete('/permissions/{id}', [Zero0719\HyperfAdmin\Controller\AdminPermissionsController::class, 'destroy'], ['name' => 'permissionDestroy']);

            Router::post('/syncRoleToUser', [Zero0719\HyperfAdmin\Controller\RbacController::class, 'syncRoleToUser'], ['name' => 'syncRoleToUser']);
            Router::post('/syncPermissionToRole', [Zero0719\HyperfAdmin\Controller\RbacController::class, 'syncPermissionToRole'], ['name' => 'syncPermissionToRole']);
        }, ['middleware' => [\Zero0719\HyperfAdmin\Middleware\RbacMiddleware::class]]);
    }, ['middleware' => [Phper666\JWTAuth\Middleware\JWTAuthDefaultSceneMiddleware::class]]);
});
/*---admin route end---*/
EOT;

    public function handle()
    {
        if (!Db::getSchemaBuilder()->hasTable('admin_users')) {
            $this->call('admin:migrate');
        };

        if (AdminUsers::count() > 0) {
            $answer = $this->ask('已有用户数据，需要重装吗?[y/n]');
            
            if ($answer != 'y') {
                return $this->error('取消执行');
            }

            // 清空表
            AdminUsers::truncate();
            AdminRoles::truncate();
            AdminPermissions::truncate();
        }

        while (true) {
            $username = $this->ask('请输入管理员用户名:');

            if (strlen($username) < 5) {
                $this->error('用户名长度不能小于5');
                continue;
            }
            break;
        }

        while (true) {
            $password = $this->ask('请输入管理员密码:');

            if (strlen($password) < 5) {
                $this->error('密码长度不能小于5');
                continue;
            }
            break;
        }

        if (!AdminUsers::create([
            'username' => $username,
            'password' => $password
        ])) {
            return $this->error('创建管理员失败');
        }

        $this->info(sprintf('系统用户已创建, 用户名: %s, 密码: %s', $username, $password), 'success');

        // 生成初始权限
        $now = date('Y-m-d H:i:s');
        $permissions = [
            ['name' => '用户列表', 'flag' => 'userList', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '创建用户', 'flag' => 'userCreate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '更新用户', 'flag' => 'userUpdate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '删除用户', 'flag' => 'userDestroy', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '角色列表', 'flag' => 'roleList', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '创建角色', 'flag' => 'roleCreate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '更新角色', 'flag' => 'roleUpdate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '删除角色', 'flag' => 'roleDestroy', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '权限列表', 'flag' => 'permissionList', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '创建权限', 'flag' => 'permissionCreate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '更新权限', 'flag' => 'permissionUpdate', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '删除权限', 'flag' => 'permissionDestroy', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '同步角色到用户', 'flag' => 'syncRoleToUser', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '同步权限到角色', 'flag' => 'syncPermissionToRole', 'created_at' => $now, 'updated_at' => $now]
        ];

        AdminPermissions::insert($permissions);
        $this->info('权限列表初始化完成.');

        // 替换 route 菜单
        $this->replaceRoute();

        $this->info('系统安装成功.');
    }

    private function replaceRoute()
    {
        $answer = $this->ask('路由覆盖会影响原来的路由配置，甚至影响系统, 是否继续?[y/n]', 'n');
        
        if ($answer != 'y') {
            return;
        }

        $file = BASE_PATH . '/config/routes.php';

        $startKeyWord = '/*---admin route begin---*/';
        $endKeyWord = '/*---admin route end---*/';

        // 在 routes.php 中查找$startKeyword 到 $endKeyWord 关键字，如果有则替换这段内容为 $this->routeStrings,如果没有，则在末尾插入
        $routeString = file_get_contents($file);
        if (strpos($routeString, $startKeyWord) !== false && strpos($routeString, $endKeyWord) !== false) {
            $startIndex = strpos($routeString, $startKeyWord);
            $endIndex = strpos($routeString, $endKeyWord);
            $routeString = substr_replace($routeString, $this->routeString, $startIndex, $endIndex - $startIndex + strlen($endKeyWord));
        } else {
            $routeString .= PHP_EOL . $this->routeString;
        }
        
        file_put_contents($file, $routeString);

        $this->info('路由配置文件已更新.');
    }

}