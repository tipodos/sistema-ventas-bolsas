<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\sale;
use App\Models\product;
use App\Models\category;
use App\Models\personal;
use App\Models\saleDatail;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hoy = date('Y-m-d');
        $mesActual = date('m');
        $anioActual = date('Y');

        // 1. Resumen del Día
        $totalVentasHoy = sale::whereDate('created_at', $hoy)->sum('total');
        $ventasYape = sale::whereDate('created_at', $hoy)->where('metodo_pago', 'yape')->sum('total');
        $ventasEfectivo = sale::whereDate('created_at', $hoy)->where('metodo_pago', 'efectivo')->sum('total');

        // 2. Resumen del Mes
        $totalVentasMes = sale::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->sum('total');

        // 3. Stock Crítico (Menos de 6 unidades)
        $productosCriticos = product::where('stock', '<=', 5)->get();
        $conteoCriticos = $productosCriticos->count();

        // 4. Top 5 Productos más vendidos (Histórico)
        $topProductos = saleDatail::select('product_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('product_id')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->with('producto')
            ->get();

        return view('show', compact(
            'totalVentasHoy',
            'ventasYape',
            'ventasEfectivo',
            'totalVentasMes',
            'productosCriticos',
            'conteoCriticos',
            'topProductos'
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
