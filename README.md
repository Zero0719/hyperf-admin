一个后台管理项目最基本的就是权限控制，所以基本上实现了 `RBAC` 模型，再配合相应的前端后台模板，就完成了一个简单后台的部署，后面再根据业务需要进行响应的开发。

## 特性

* 会话控制
* 用户管理
* 角色管理
* 权限管理
* RBAC控制

## 安装&部署

```php
composer require zero0719/hyperf-admin
```

执行初始化命令
```php
php bin/hyperf.php admin:install
```

配置 admin.php

配置 JWT

```php
'no_check_route' => [
    ['post', '/sessions'],
],
```

配置中间件
```php
return [
    'http' => [
        \Zero0719\HyperfApi\Middleware\CorsMiddleware::class,
        \Zero0719\HyperfApi\Middleware\RequestLogMiddleware::class,
        \Hyperf\Validation\Middleware\ValidationMiddleware::class
    ],
];
```

配置异常处理
```php
return [
    'handler' => [
        'http' => [
            \Zero0719\HyperfApi\Exception\Handler\ValidationExceptionHandler::class,
            \Zero0719\HyperfApi\Exception\Handler\JWTExceptionHandler::class,
            \Zero0719\HyperfApi\Exception\Handler\ModelNotFoundExceptionHandler::class,
            \Zero0719\HyperfApi\Exception\Handler\BusinessExceptionHandler::class,
            \Zero0719\HyperfApi\Exception\Handler\AppExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
```

## 其他

后续开发的路由，可以参考 `routes.php`，分为登录未登录，鉴权和非鉴权

原有的逻辑也可以通过修改路由或者继承响应的控制器以后进行重写














