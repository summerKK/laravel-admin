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

    /**
     * @param $pictures
     * @return mixed
     */
    public function getMediaGalleryAttribute($pictures)
    {
        if (is_string($pictures)) {
            return array_map(function ($item) {
                return str_replace(config('app.upload_dir'), '', $item);
            }, explode(';', rtrim($pictures, ';')));
        }

        return $pictures;
    }

    /**
     * @param $pictures
     */
    public function setMediaGalleryAttribute($pictures)
    {
        if (is_array($pictures)) {
            array_walk($pictures, function (&$item) {
                $item = config('app.upload_dir') . $item;
            });

            $this->attributes['media_gallery'] = implode(';', $pictures);
        }
    }

}
