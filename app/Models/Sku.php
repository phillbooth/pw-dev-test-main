<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'product_id', 'SKU', 'box_qty', 'width', 'height', 'length',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
