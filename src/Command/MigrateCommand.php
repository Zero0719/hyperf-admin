<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;

#[Command]
class MigrateCommand extends HyperfCommand
{
    protected ?string $name = 'admin:migrate';
    
    public function handle()
    {
        $this->call('migrate', [
            '--path' => 'vendor/zero0719/hyperf-admin/src/migrations'
        ]);
    }
}