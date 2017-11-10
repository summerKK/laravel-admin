<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vendor
 *
 * @property int $id
 * @property string $vendor_store 店铺名称
 * @property string $vendor_hour 店铺运营时间
 * @property string $vendor_logo
 * @property string $vendor_geo 地理位置(保存的经纬度)
 * @property string $vendor_mtr_tw 地铁地址(繁体)
 * @property string $vendor_mtr_cn 地铁地址(简体)
 * @property string $vendor_mtr 地铁地址(英文)
 * @property string $vendor_addr_tw 店铺位置(繁体)
 * @property string $vendor_addr_cn 店铺位置(简体)
 * @property string $vendor_addr 店铺位置(英文)
 * @property string $vendor_intro_tw 店铺介绍(繁体)
 * @property string $vendor_intro_cn 店铺介绍(简体)
 * @property string $vendor_intro 店铺介绍(英文)
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorAddr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorAddrCn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorAddrTw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorGeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorIntroCn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorIntroTw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorMtr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorMtrCn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorMtrTw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vendor whereVendorStore($value)
 * @mixin \Eloquent
 */
class Vendor extends Model
{
    protected $table = 'lux_vendor';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Products::class, 'vendor_id', 'id');
    }
}
