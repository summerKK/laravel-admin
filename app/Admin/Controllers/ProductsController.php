<?php

namespace App\Admin\Controllers;

use App\Models\GoodsType;
use App\Models\Products;

use App\Models\User;
use App\Models\Vendor;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProductsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Products::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->author('author')->display(function ($value) {
                if ($value === 0) {
                    return 'crawler';
                }

                return Administrator::find($value)->name;
            });
            $grid->src('src')->display(function ($value) {
                $src = [
                    1 => 'CRAWLER',
                    2 => 'EXCEL',
                    3 => 'API',
                ];

                return $src[$value];
            });
            $grid->sku('sku');
            $grid->name_chs('name')->limit(20);
            $grid->media_gallery('images')->display(function ($pictures) {
                return str_replace('/luxsens/robot/imgs/', '', explode(';', rtrim($pictures, ';'))[0]);
            })->image('http://productdb.luxsens.com/', 100, 100);
            $grid->qty('quantity');
            $grid->item_condition('condition');
            $grid->price('sell price');
            $grid->cost('cost');
            $grid->market_price('market price');
            $grid->product_currency('currency');
            $grid->brand('brand');
            $grid->collection('collection');
            $grid->item_style('style');
            $grid->back_sku('original sku');

            $grid->created_at()->sortable();
            $grid->updated_at()->sortable();

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Products::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('author')->with(function ($value) {
                if ($value === 0) {
                    return 'crawler';
                }

                return Administrator::find($value)->name;
            });

            $form->display('src')->with(function ($value) {
                $src = [
                    1 => 'CRAWLER',
                    2 => 'EXCEL',
                    3 => 'API',
                ];

                return $src[$value];
            });

            $form->display('sku');
            $form->text('name_chs', 'name(chs)');
            $form->text('name_cht', 'name(cht)');
            $form->text('name_eng', 'name(eng)');
            $form->select('audit')->options([0 => 'no', 1 => 'yes'])->help('确认爬虫爬回来的数据是否已经审核过 0 未审核 1已审核');
            $form->display('grade', 'clawler_error')->with(function ($value) {
                return [1 => 'error', 2 => 'good'][$value];
            });
            $form->number('qty', 'quantity');
            $form->select('status')->options([1 => 'enable', 2 => 'disable']);
            $form->display('vendor')->with(function ($value) {
                if ($value) {
                    return Vendor::find($value)->name;
                }

                return '';
            });
            $form->text('item_country', 'country');
            $form->text('item_city', 'city');
            $form->text('item_condition', 'condition');
            $form->select('attribute_set', 'goods type')->options(GoodsType::all()->pluck('name', 'id'));
            $form->decimal('price', 'sell price');
            $form->decimal('cost', 'cost');
            $form->decimal('market_price', 'market price');
            $form->select('product_currency', 'currency')->options(['HKD' => 'HKD', 'CNY' => 'CNY', 'USD' => 'USD']);
            $form->text('brand', 'brand');
            $form->text('collection', 'collection');
            $form->text('item_style', 'style');
            $form->image('media_gallery', 'media gallery')->move('1', '2')->removable();

            $form->display('created_at', 'created at');
            $form->display('updated_at', 'updated at');
        });
    }
}
