<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Concept;
use App\Models\Item;
use App\Models\LaporanSore;
use App\Models\Production;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isGudang()) {
            return view('dashboard.home', [
                'counts' => [
                    'laporan_sore' => LaporanSore::query()->count(),
                ],
            ]);
        }

        return view('dashboard.home', [
            'counts' => [
                'units' => Unit::query()->count(),
                'categories' => Category::query()->count(),
                'items' => Item::query()->count(),
                'concepts' => Concept::query()->count(),
                'productions' => Production::query()->biasa()->count(),
                'treatments' => Production::query()->treatment()->count(),
                'laporan_sore' => LaporanSore::query()->count(),
            ],
        ]);
    }
}
