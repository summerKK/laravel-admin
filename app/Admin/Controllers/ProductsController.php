<?php

namespace App\Admin\Controllers;

use App\Models\Products;

use App\Models\User;
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
            $grid->price('sellPrice');
            $grid->cost('cost');
            $grid->market_price('marketPrice');
            $grid->product_currency('currency');
            $grid->brand('brand');
            $grid->collection('collection');
            $grid->item_style('style');
            $grid->back_sku('originalSku');

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
            $form->display('name_chs');
            $form->display('name_cht');
            $form->display('name_eng');
            $form->select('audit')->options([0 => '未审核', 1 => '已审核'])->help('确认爬虫爬回来的数据是否已经审核过 0 未审核 1已审核');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
