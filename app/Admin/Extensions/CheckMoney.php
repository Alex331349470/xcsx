<?php


namespace App\Admin\Extensions;


class CheckMoney
{
    protected function script()
    {
        return <<<SCRIPT

$('.check-draw-money').on('click', function () {
     $.ajax({
        type : "get",
        url : "http://car.agelove.cn/api/v1/test",
        dataType : "json",
        success : function(test) {
            window.location.reload();
        },
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success check-draw-money' >通过</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}