<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Products
 *
 * @property int $id
 * @property int $author 0代表爬虫
 * @property int $src 数据来源1.爬虫,2.excel,3.api
 * @property string $sku
 * @property string $name_chs 商品名称(中文)
 * @property string $name_cht 商品名称(繁体)
 * @property string $name_eng 商品名称(英文)
 * @property int $audit 确认爬虫爬回来的数据是否已经审核过 0 未审核 1已审核
 * @property int $grade 运行clean的时候数据是否有问题(1.没问题,2.有问题)
 * @property int $qty 商品数量
 * @property int $status 商品状态,1.正常,2.禁用
 * @property int $on_sale 1.出售,2.不出售
 * @property int|null $vendor_id 供应商(vendor表的id)
 * @property int $is_in_stock 是否有现货,1.有,2.无
 * @property string $category_ids (Wrist Watches 73) (Shoulder Bags 72) (WALLET 74) 8有折扣 45所有
 * @property string $item_country
 * @property string $item_city
 * @property string $thumbnail
 * @property string $item_condition
 * @property string $image 首页
 * @property int $attribute_set lux_goods_type表的id 商品类型(包或者表)
 * @property float $price 销售价格
 * @property float|null $cost 从别的平台购买的花费(成本价)
 * @property float|null $market_price 市场价
 * @property float|null $special_price 折扣价(目前只有VIPSTATION有折扣价 price * 0.95)
 * @property string $product_currency
 * @property string $brand
 * @property string $collection
 * @property string $item_style 商品风格(肩背包,手提包...)
 * @property mixed $media_gallery 图片库
 * @property string|null $description
 * @property string $back_sku
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Encore\Admin\Auth\Database\Administrator $authorName
 * @property-read \App\Models\GoodsType $goodsType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereAttributeSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereAudit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereBackSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereCategoryIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereIsInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereItemCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereItemCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereItemCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereItemStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereMarketPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereMediaGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereNameChs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereNameCht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereNameEng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereProductCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereSpecialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products whereVendorId($value)
 * @mixin \Eloquent
 */
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

    /**
     * @param $vendorId
     * @return mixed|string
     */
    public function getVendorIdAttribute($vendorId)
    {
        if ($vendorName = Vendor::find($vendorId)) {
            return $vendorName->vendor;
        }

        return '';
    }

    /**
     * @param $vendorName
     * @return int|mixed|null
     */
    public function setVendorIdAttribute($vendorName)
    {
        $vendor = Vendor::where('vendor', $vendorName)->first();
        if ($vendor) {
            return $vendor->id;
        }

        return null;
    }

}
