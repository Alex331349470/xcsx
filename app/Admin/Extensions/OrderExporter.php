<?php


namespace App\Admin\Extensions;


use Encore\Admin\Grid\Exporters\ExcelExporter;

class OrderExporter extends ExcelExporter
{
    protected $fileName = '收入列表.xlsx';

    protected $columns = [
        'id' => 'ID',
        'no' => '订单号',
        'paid_at' => '付款时间',
        'income' => '收入',
        'pay_man' => '付款人'
    ];
}