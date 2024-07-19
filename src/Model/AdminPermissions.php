<?php

declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * 
 */
class AdminPermissions extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'admin_permissions';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'name', 'flag'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];
}
