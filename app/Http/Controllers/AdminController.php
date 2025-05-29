<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getEarnings()
    {
        $totalEarnings = Purchase::sum('tax');

        $totalPurchases = Purchase::count();

        return response()->json([
            'total_earnings' => $totalEarnings,
            'total_purchases' => $totalPurchases,
            'average_tax' => $totalPurchases > 0 ? $totalEarnings / $totalPurchases : 0
        ]);
    }
}
