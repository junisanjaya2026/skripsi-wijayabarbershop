<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {
        /*
        |----------------------------------------------------------------
        | TOTAL PESANAN (all-time)
        |----------------------------------------------------------------
        */
        $totalOrders = Order::count();
 
        /*
        |----------------------------------------------------------------
        | PESANAN HARI INI (count + omzet)
        | whereDate('created_at', today()) otomatis hanya mengambil data
        | hari berjalan -> "reset" tiap hari tanpa perlu job/cron manual.
        |----------------------------------------------------------------
        */
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
 
        $todayOrdersRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', ['settlement', 'success', 'completed']) // sesuaikan status "sudah dibayar/selesai" di sistemmu
            ->sum('grand_total');
 
        /*
        |----------------------------------------------------------------
        | JUMLAH MENU
        |----------------------------------------------------------------
        */
        $totalMenu = Item::count();
 
        /*
        |----------------------------------------------------------------
        | JUMLAH KARYAWAN
        | role_id: 1,2,3 dianggap karyawan (mis. admin, kasir, dsb),
        | role_id 4 = guest/customer dikecualikan.
        |----------------------------------------------------------------
        */
        $totalEmployee = User::whereIn('role_id', [1, 2, 3])->count();
 
        /*
        |----------------------------------------------------------------
        | GRAFIK PENJUALAN 7 HARI TERAKHIR
        |----------------------------------------------------------------
        */
        $salesRaw = Order::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->whereIn('status', ['settlement', 'success', 'completed'])
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->get()
            ->keyBy('date');
 
        $chartLabels = [];
        $chartData = [];
 
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
            $chartData[] = isset($salesRaw[$date]) ? (float) $salesRaw[$date]->total : 0;
        }
 
        return view('admin.dashboard', compact(
            'totalOrders',
            'todayOrdersCount',
            'todayOrdersRevenue',
            'totalMenu',
            'totalEmployee',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
