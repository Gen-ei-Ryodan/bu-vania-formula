<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(
            Item::query()->with(['category', 'defaultUnit', 'priceUnit'])->orderBy('name')->get()
        );
    }

    public function show(Item $item)
    {
        return response()->json($item->load(['category', 'defaultUnit', 'priceUnit']));
    }
}
