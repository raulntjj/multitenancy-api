<?php

namespace App\Tenant\Traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\HandleException;

trait HasUserActions {
    use \Illuminate\Database\Eloquent\SoftDeletes;

    public static function bootHasUserActions() {
        $token = request()->bearerToken();
        
        $decoded = (object) ['sub' => NULL];
        
        try {
            if ($token) {
                $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            }
        } catch (ExpiredException $e) {
            throw new HandleException('Token expired', 400);
        } catch (\Exception $e) {
            throw new HandleException('Token ' . $e->getMessage(), $e->getCode());
        }

        static::creating(function (Model $model) use ($decoded) {
            $model->created_by = $decoded->sub ?? NULL;
        });
    
        static::updating(function (Model $model) use ($decoded) {
            $model->updated_by = $decoded->sub ?? NULL;
        });
    
        static::deleting(function (Model $model) use ($decoded) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                $model->deleted_by = $decoded->sub ?? NULL;
                $model->saveQuietly();
            }
        });
    }
    public function initializeHasUserActions() {
        $this->mergeFillable(['created_by', 'updated_by', 'deleted_by']);
    }
}
