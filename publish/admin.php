<?php

use function Hyperf\Support\env;

return [
    'password_salt' => env('PASSWORD_SALT', ''),
    'jwt' => [
        'custom_claims' => ['id', 'username']
    ],
    'white_list' => [
        // 用户用id
        'users' => [1],

        // 角色用flag
        'roles' => ['admin']
    ],
];