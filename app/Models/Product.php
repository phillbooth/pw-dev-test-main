<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'product_name', 'parent_category', 'description', 'on_sale', 'price', 'updated_at',
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

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    // Add this method to calculate discount
    public function applyDiscount(float $percentage): float
    {
        return round($this->price * ((100 - $percentage) / 100), 2);
    }
}
