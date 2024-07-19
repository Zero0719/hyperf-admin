一个后台管理项目最基本的就是权限控制，所以基本上实现了 `RBAC` 模型，再配合相应的前端后台模板，就完成了一个简单后台的部署，后面再根据业务需要进行响应的开发。

## 特性

* 会话控制
* 用户管理
* 角色管理
* 权限管理
* RBAC控制

## 安装&部署

todo

发布配置文件

```php
php bin/hyperf.php vendor:publish zero0719/hyperf-admin
```

执行初始化命令
```php
php bin/hyperf.php admin:init
```

## 配置文件 admin.php

重点关注白名单用户ID和白名单角色标识即可












