<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\disabledGoods;
use App\Models\GoodsType;
use App\Models\Products;

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

            $content->header('Goods');
            $content->description('List');

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

            $content->header('Goods');
            $content->description('Edit Goods');

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

    public function disableProducts($id)
    {

        return false;
        Products::find($id)->update(['grade' => 2, 'status' => 2]);

        return true;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Products::class, function (Grid $grid) {

            $grid->disableCreation();

            $id = $grid->getKeyName('id');

            $grid->actions(function ($actions) use ($id) {
                $actions->disableDelete();
                // append一个操作
                $actions->append(new disabledGoods($actions->getKey()));
            });

            $grid->id()->sortable();
            $grid->author()->display(function ($value) {
                if ($value === 0) {
                    return 'crawler';
                }

                return Administrator::find($value)->name;
            });
            $grid->src()->display(function ($value) {
                $src = [
                    1 => 'CRAWLER',
                    2 => 'EXCEL',
                    3 => 'API',
                ];

                return $src[$value];
            });
            $grid->sku();
            $grid->name_eng()->limit(20);
            $grid->media_gallery('Images')->display(function ($pictures) {
                return $pictures[0];
            })->image('', 100, 100);
            $grid->qty('Quantity');
            $grid->item_condition('Condition');
            $grid->price('Sell Price');
            $grid->cost('Cost');
            $grid->market_price('Market Price');
            $grid->product_currency('Currency');
            $grid->brand();
            $grid->collection();
            $grid->item_style('Style');
            $grid->back_sku('Original Sku');


            $grid->filter(function ($filter) {

                $filter->disableIdFilter();

                $filter->like('name_eng', 'Name(eng)');
                $filter->like('brand');
                $filter->like('sku');
                $filter->like('back_sku', 'Original Sku');
                $filter->like('collection');

                $filter->equal('attribute_set', 'Goods Type')->select(GoodsType::all()->pluck('name', 'id'));
                $filter->equal('audit')->select([0 => 'no', 1 => 'yes']);
                $filter->equal('grade', 'Clawler Error')->select([1 => 'good', 2 => 'error']);
                $filter->equal('status', 'Product Status')->select([1 => 'enable', 2 => 'disable']);

                $filter->lt('price')->integer();
                $filter->gt('price')->integer();
                $filter->lt('cost')->integer();
                $filter->gt('cost')->integer();
                $filter->between('updated_at')->datetime();
                $filter->between('created_at')->datetime();

            });

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
                if (!$value) {
                    return;
                }
                if ($value === 0) {
                    return 'crawler';
                }

                return Administrator::find($value)->name;
            });

            $form->display('src')->with(function ($value) {
                if (!$value) {
                    return;
                }
                $src = [
                    1 => 'CRAWLER',
                    2 => 'EXCEL',
                    3 => 'API',
                ];

                return $src[$value];
            });

            $form->display('sku');
            $form->text('name_chs', 'Name(chs)');
            $form->text('name_cht', 'Name(cht)');
            $form->text('name_eng', 'Name(eng)');
            $form->select('audit')->options([0 => 'no', 1 => 'yes'])->help('确认爬虫爬回来的数据是否已经审核过 0 未审核 1已审核');
            $form->display('grade', 'Clawler Error')->with(function ($value) {
                if (!$value) {
                    return;
                }

                return [1 => 'error', 2 => 'good'][$value];
            });
            $form->number('qty', 'Quantity');
            $form->select('status')->options([1 => 'enable', 2 => 'disable']);
            $form->display('vendor')->with(function ($value) {
                if (!$value) {
                    return;
                }
                if ($value) {
                    return Vendor::find($value)->name;
                }
            });
            $form->text('item_country', 'Country');
            $form->text('item_city', 'City');
            $form->text('item_condition', 'Condition');
            $form->select('attribute_set', 'Goods Type')->options(GoodsType::all()->pluck('name', 'id'));
            $form->decimal('price', 'Sell Price');
            $form->decimal('cost', 'Cost');
            $form->decimal('market_price', 'Market Price');
            $form->select('product_currency', 'Currency')->options(['HKD' => 'HKD', 'CNY' => 'CNY', 'USD' => 'USD']);
            $form->text('brand', 'Brand');
            $form->text('collection', 'Collection');
            $form->text('item_style', 'Style');
            $form->multipleImage('media_gallery', 'Media Gallery')->removable();

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
//                $form->set
            });

        });
    }
}
