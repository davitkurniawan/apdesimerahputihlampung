<?php
if (defined('XOSID_CONFIG_ID_SCOPE_HIJACK_LOADED')) {
    return;
}
define('XOSID_CONFIG_ID_SCOPE_HIJACK_LOADED', true);

/**
 * XOSID - ConfigId Scope Hijack for Multi-Tenant Database Consistency
 * Forces config_id to be resolved from CodeIgniter session (typically 1)
 * instead of the master registry ID (typically 10 for Bondo).
 */

if (!trait_exists('App\Traits\ConfigId')) {
    eval(<<<'CODE'

namespace App\Scopes {
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Scope;

    class ConfigIdScope implements Scope
    {
        public function apply(Builder $builder, Model $model)
        {
            $config_id = isset($_SESSION['config_id']) ? (int)$_SESSION['config_id'] : 1;
            
            // Log scope application for debugging
            $log = "[" . date('Y-m-d H:i:s') . "] ConfigIdScope applied to " . get_class($model) . " with config_id=" . $config_id . "\n";
            @file_put_contents(FCPATH . 'storage/logs/xosid_scope_debug.log', $log, FILE_APPEND);
            
            $builder->where($model->getTable() . '.config_id', $config_id);
        }

        public function extend(Builder $builder)
        {
            // Define macro to remove the scope if needed
            $builder->macro('withoutConfigId', function (Builder $builder) {
                return $builder->withoutGlobalScope($this);
            });
        }
    }

    class ConfigIdNullScope implements Scope
    {
        public function apply(Builder $builder, Model $model)
        {
            $config_id = isset($_SESSION['config_id']) ? (int)$_SESSION['config_id'] : 1;
            
            $log = "[" . date('Y-m-d H:i:s') . "] ConfigIdNullScope applied to " . get_class($model) . " with config_id=" . $config_id . "\n";
            @file_put_contents(FCPATH . 'storage/logs/xosid_scope_debug.log', $log, FILE_APPEND);

            $builder->where(function ($query) use ($model, $config_id) {
                $query->where($model->getTable() . '.config_id', $config_id)
                      ->orWhereNull($model->getTable() . '.config_id');
            });
        }

        public function extend(Builder $builder)
        {
            $builder->macro('withoutConfigIdNull', function (Builder $builder) {
                return $builder->withoutGlobalScope($this);
            });
        }
    }

    class AccessWilayahScope implements Scope
    {
        public function apply(Builder $builder, Model $model)
        {
            $log = "[" . date('Y-m-d H:i:s') . "] AccessWilayahScope bypassed for " . get_class($model) . "\n";
            @file_put_contents(FCPATH . 'storage/logs/xosid_scope_debug.log', $log, FILE_APPEND);
        }

        public function extend(Builder $builder)
        {
            $builder->macro('withoutAccessWilayah', function (Builder $builder) {
                return $builder->withoutGlobalScope($this);
            });
        }
    }

    class WilayahNullScope implements Scope
    {
        public function apply(Builder $builder, Model $model)
        {
            $log = "[" . date('Y-m-d H:i:s') . "] WilayahNullScope bypassed for " . get_class($model) . "\n";
            @file_put_contents(FCPATH . 'storage/logs/xosid_scope_debug.log', $log, FILE_APPEND);
        }

        public function extend(Builder $builder)
        {
            $builder->macro('withoutWilayahNull', function (Builder $builder) {
                return $builder->withoutGlobalScope($this);
            });
        }
    }
}

namespace App\Observers {
    use Illuminate\Database\Eloquent\Model;

    class ConfigIdObserver
    {
        public function creating(Model $model)
        {
            $config_id = isset($_SESSION['config_id']) ? (int)$_SESSION['config_id'] : 1;
            
            $log = "[" . date('Y-m-d H:i:s') . "] ConfigIdObserver setting config_id=" . $config_id . " on creating " . get_class($model) . "\n";
            @file_put_contents(FCPATH . 'storage/logs/xosid_scope_debug.log', $log, FILE_APPEND);

            $model->setAttribute('config_id', $config_id);
        }
    }
}

namespace App\Traits {
    trait ConfigId
    {
        public static function bootConfigId()
        {
            static::addGlobalScope(new \App\Scopes\ConfigIdScope);
            static::observe(new \App\Observers\ConfigIdObserver);
        }
    }

    trait ConfigIdNull
    {
        public static function bootConfigIdNull()
        {
            static::addGlobalScope(new \App\Scopes\ConfigIdNullScope);
            static::observe(new \App\Observers\ConfigIdObserver);
        }
    }
}

CODE
    );
}
