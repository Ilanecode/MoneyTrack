<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'model', 'model_id',
        'description', 'ip_address', 'user_agent'
    ];

    public static function log($action, $description, $model = null, $modelId = null)
    {
        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
