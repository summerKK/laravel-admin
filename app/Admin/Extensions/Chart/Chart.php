<?php
/**
 * Created by PhpStorm.
 * User: Summer
 * Date: 2017/11/20
 * Time: 11:24
 */

namespace App\Admin\Extensions\Chart;

use App\Models\Products;
use Illuminate\Support\Facades\DB;

class Chart
{
    public static function chart()
    {
        return view('Dashboard.chart', [
            'data' => [
                'brands'   => self::statisticsByBrands(),
                'type'     => self::statisticsByType(),
                'country'  => self::statisticsByCountry(),
                'country1' => self::statisticsByCountry1(),
            ],
        ]);
    }

    public static function statisticsByBrands()
    {
        $brands     = Products::selectRaw('count(*) count,brand')
            ->groupBy('brand')
            ->get();
        $formatData = ['legendData' => [], 'seriesData' => []];
        foreach ($brands as $brand) {
            array_push($formatData['legendData'], strtoupper($brand->brand));
            array_push($formatData['seriesData'], ['name' => strtoupper($brand->brand), 'value' => $brand->count]);
        }

        return json_encode($formatData);

    }

    public static function statisticsByType()
    {

        $conditions = Products::selectRaw('item_condition')
            ->groupBy('item_condition')
            ->get();

        $datas      = DB::table('lux_products as a')
            ->leftJoin('lux_goods_type as b', 'a.attribute_set', '=', 'b.id')
            ->selectRaw('count(*) count,b.name,a.item_condition')
            ->groupBy(['attribute_set', 'item_condition'])
            ->get();
        $formatData = [
            'category'  => [],
            'condition' => [],
        ];
        $mergeData  = [];
        //先把数据格式化一下,通过goodstype分组
        foreach ($datas as $k => $data) {

            if (!array_key_exists($data->name, $mergeData)) {
                $mergeData[$data->name] = [];
            }

            $mergeData[$data->name][$data->item_condition] = $data->count;

        }

        //查找$mergeData数组里面的属性是否全部存在,不存在的补0
        //因为取数据是按照数据的顺序来取得如果少一个属性,数据就会错乱
        foreach ($mergeData as $k => $item) {
            foreach ($conditions as $condition) {
                if (!array_key_exists($condition->item_condition, $item)) {
                    $mergeData[$k][$condition->item_condition] = 0;
                }
            }
        }

        foreach ($mergeData as $key => $item) {
            if (!in_array($key, $formatData['category'], true)) {
                $formatData['category'][] = $key;
            }
            foreach ($item as $k => $v) {
                if (!array_key_exists($k, $formatData)) {
                    $formatData['condition'][] = $k;
                    $formatData[$k]            = [];
                }
                array_push($formatData[$k], $v);
            }
        }

        $legend = "['" . implode($formatData['condition'], "','") . "']";
        $yAxis  = "['" . implode($formatData['category'], "','") . "']";

        $series = '';
        foreach ($formatData['condition'] as $condition) {
            $data      = '[' . implode($formatData[$condition], ',') . ']';
            $condition = "'" . $condition . "'";
            $series    .= <<<STR
        {
            name: $condition,
            type: 'bar',
            data: $data,
        },
STR;

        }

        $config = <<<STR
option = {
    title: {
        text: 'Category Statistics',
    },
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    legend: {
        data: $legend
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis: {
        type: 'value',
        boundaryGap: [0, 0.01]
    },
    yAxis: {
        type: 'category',
        data: $yAxis
    },
    series: [
        $series
    ]
};
STR;


        return $config;

    }

    public static function statisticsByCountry()
    {
        $countries = Products::selectRaw('count(*) count,item_country country')
            ->groupBy('item_country')
            ->get();

        $formatData = [];
        foreach ($countries as $country) {
            $formatData[] = [
                'name'  => $country->country,
                'value' => $country->count,
            ];
        }

        return json_encode($formatData);
    }

    public static function statisticsByCountry1()
    {
        $countries = Products::selectRaw('count(*) count,item_country country')
            ->groupBy('item_country')
            ->get();

        $formatData = [
            'legend' => [],
            'series' => [],
        ];
        foreach ($countries as $country) {
            if (!$country->country) {
                $country->country = 'Unknown';
            }
            $name = ucfirst($country->country) . "($country->count)";
            if (!in_array($name, $formatData['legend'], true)) {
                array_push($formatData['legend'], $name);
                array_push($formatData['series'], ['value' => $country->count, 'name' => $name]);
            }
        }

        return [
            'legend' => json_encode($formatData['legend']),
            'series' => json_encode($formatData['series']),
        ];
    }
}