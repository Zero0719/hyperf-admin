<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
                \Zero0719\HyperfAdmin\Command\MigrateCommand::class,
                \Zero0719\HyperfAdmin\Command\AdminInstallCommand::class
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of hyperf admin.',
                    'source' => __DIR__ . '/../publish/admin.php',
                    'destination' => BASE_PATH . '/config/autoload/admin.php',
                ],
            ],
        ];
    }
}