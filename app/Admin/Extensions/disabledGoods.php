<?php
/**
 * Created by PhpStorm.
 * User: Summer
 * Date: 2017/11/9
 * Time: 17:12
 */

namespace App\Admin\Extensions;

use App\Models\Products;
use Encore\Admin\Admin;

class disabledGoods
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Script of batch disable action.
     */
    protected function script()
    {
        $disableConfirm = trans('admin.disable_confirm');
        $confirm        = trans('admin.confirm');
        $cancel         = trans('admin.cancel');

        return <<<EOT

$('.disable_goods').on('click', function() {

    swal({
      title: "$disableConfirm",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: '/admin/products/disable/{$this->id}',
            data: {
                _token:'{$this->getToken()}'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

EOT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='fa fa-close disable_goods' data-id='{$this->id}'></a>";

    }

    protected function getToken()
    {
        return csrf_token();
    }

    public function __toString()
    {
        return $this->render();
    }

}