<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    /**
     * Boot function.
     */
    protected static function bootHasUUID(): void
    {
        static::creating(function ($model) {
            $model->{$model->getUUIDKey()} = Str::uuid()->toString();
        });
    }

    /**
     * Get the uuid key name.
     *
     * @return string
     */
    public function getUUIDKey(): string
    {
        return 'uuid';
    }
}
