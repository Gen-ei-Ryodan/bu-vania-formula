<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Item;
use App\Models\Production;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.home', [
            'counts' => [
                'units' => Unit::query()->count(),
                'items' => Item::query()->count(),
                'concepts' => Concept::query()->count(),
                'productions' => Production::query()->count(),
            ],
        ]);
    }
}
