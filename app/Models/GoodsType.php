<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GoodsType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoodsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoodsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoodsType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoodsType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsType extends Model
{
    protected $table = 'lux_goods_type';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Products::class, 'attribute_set', 'id');
    }
}
