<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class CarStart extends RowAction
{
    public $name = '打开';

    public function handle(Model $model)
    {
        // $model ...
        $car_num = $model->serial_num;

        return $this->response()->success('Success message.')->refresh();
    }

}