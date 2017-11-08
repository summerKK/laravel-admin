<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'lux_products';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * lux_goods_type
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function goodsType()
    {
        return $this->hasOne(GoodsType::class, 'id', 'attribute_set');
    }

    /**
     * admin_users
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authorName()
    {
        return $this->hasOne(Administrator::class, 'id', 'author');
    }

}
