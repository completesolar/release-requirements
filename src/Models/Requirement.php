<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $stage
 *
 * @mixin Eloquent
 */
class Requirement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'stage',
    ];
}
