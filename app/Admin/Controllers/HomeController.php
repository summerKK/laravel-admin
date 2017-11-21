<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Chart\Chart;
use App\Admin\Extensions\Currency;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Vendor;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $summarize = $this->summarize();

            $content->row(function ($row) use ($summarize) {
                $row->column(2,
                    new InfoBox('All Products', 'shopping-bag', 'aqua', '/admin/products', $summarize['products']));
                $row->column(2, new InfoBox('Value', 'money', 'green', '', $summarize['moneies']));
                $row->column(2, new InfoBox('Vendors', 'users', 'yellow', '', $summarize['vendors']));
                $row->column(2, new InfoBox('Cities', 'building-o', 'red', '', $summarize['cities']));
                $row->column(2, new InfoBox('Brands', 'pie-chart', 'red', '', $summarize['brands']));
            });

            $content->row(function (Row $row) {
                $row->column('12', function (Column $column) {
                    $column->append(Chart::chart());
                });
            });
        });
    }

    protected function summarize()
    {
        if (Cache::has('STATISTICS_SUMMARIZE')) {
            return Cache::get('STATISTICS_SUMMARIZE');
        }

        $allProducts = Products::where(['grade' => 1, 'status' => 1])->select([
            'price',
            'product_currency',
        ])->get()->toArray();

        $currency   = new Currency();
        $totalMoney = 0;
        foreach ($allProducts as $k => $product) {
            if ($product['product_currency'] !== 'HKD') {
                $totalMoney += round($currency->Exchange($product['product_currency'], 'HKD', $product['price']));
            } else {
                $totalMoney += $product['price'];
            }
        }

        $vendors = Vendor::count();

        $cities = Products::groupBy('item_city')->get();

        $brands = Products::groupBy('brand')->get();


        $summarize = [
            'products' => count($allProducts),
            'moneies'  => "HKD $" . round($totalMoney / 1000000) . "M",
            'vendors'  => $vendors,
            'cities'   => count($cities),
            'brands'   => count($brands),
        ];

        Cache::put('STATISTICS_SUMMARIZE', $summarize, 1440);

        return $summarize;

    }
}

