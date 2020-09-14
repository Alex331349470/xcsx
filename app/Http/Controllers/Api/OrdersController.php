<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);
        return new OrderResource($orders);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }





}
