<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SellItemRequest;
use App\Http\Resources\SellItemResource;
use App\Models\SellItem;
use Illuminate\Http\Request;

class SellItemsController extends Controller
{
    public function index()
    {
        $sellItems = SellItem::paginate(10);
        return new SellItemResource($sellItems);
    }

    public function show(SellItem $sellItem)
    {
        return new SellItemResource($sellItem);
    }

    public function store(SellItemRequest $request, SellItem $sellItem)
    {
        $sellItem->create([
            'name' => $request->name,
            'time' => $request->time,
            'price' => $request->price,
        ]);

        return new SellItemResource($sellItem);
    }


    public function update(SellItemRequest $request, SellItem $sellItem)
    {
        $sellItem->update([
            'name' => $request->name,
            'time' => $request->time,
            'price' => $request->price
        ]);

        return new SellItemResource($sellItem);
    }

    public function destroy(SellItem $sellItem)
    {
        $sellItem->delete();

        return response(null, 204);
    }
}
