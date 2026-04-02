<?php

namespace App\Traits;

use App\Services\Admin\AuditLogService;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            app(AuditLogService::class)->logModelAction('CREATE', $model, [], $model->getAttributes());
        });
        
        static::updated(function ($model) {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges();
            
            app(AuditLogService::class)->logModelAction('UPDATE', $model, $oldValues, $newValues);
        });
        
        static::deleted(function ($model) {
            app(AuditLogService::class)->logModelAction('DELETE', $model, $model->getOriginal(), []);
        });
        
        static::restored(function ($model) {
            app(AuditLogService::class)->logModelAction('RESTORE', $model, [], $model->getAttributes());
        });
    }
}