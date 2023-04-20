<?php

namespace App\Traits;

trait ByUser
{
    public static function bootByUser(): void
    {
        static::creating(function ($model) {
            $model->created_by = $model->created_by ?? auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = $model->created_by ?? auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = $model->created_by ?? auth()->id();
            $model->save();
        });
    }
}
