<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'sku_id', 'colours', 'size', 'variant',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Override boot method to generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
}
