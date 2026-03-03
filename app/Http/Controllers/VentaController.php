<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\product;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\personal;
use App\Models\sale;
use App\Models\saleDatail;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Sale::all();
        $productos = product::all();
        $categorias = Category::all();
        $personales = Personal::all();
        return view('index', compact('ventas', 'productos', 'categorias', 'personales'));
    }

    public function export()
    {
        // Aquí puedes implementar la lógica para exportar las ventas a Excel o CSV
        // Por ejemplo, podrías usar una biblioteca como Maatwebsite/Laravel-Excel
        // para generar un archivo Excel con los datos de las ventas.
    }
    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'productos'   => 'required|array',
            'personal_id' => 'required',
            'total'       => 'required',
            'metodo_pago' => 'required' // Agregamos esto
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // 2. Crear la Venta Principal
                // Forzamos que guarde el total y metodo_pago
                $venta = sale::create([
                    'personal_id' => $request->personal_id,
                    'total'       => $request->total,
                    'metodo_pago' => $request->metodo_pago,
                ]);

                // 3. Crear el Detalle
                foreach ($request->productos as $item) {
                    saleDatail::create([
                        'sale_id'        => $venta->id,
                        'product_id'     => $item['id'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                    ]);

                    // 4. Descontar Stock
                    $producto = product::find($item['id']);
                    if ($producto) {
                        $producto->decrement('stock', $item['cantidad']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Venta registrada con éxito',
                    'venta_id' => $venta->id
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error crítico: ' . $e->getMessage()
            ], 500);
        }
    }
    public function generarTicket($id)
{
    // Buscamos la venta con sus detalles y productos
    // Asegúrate de tener las relaciones configuradas en el Modelo
    $venta = sale::with(['detalles.producto', 'personal'])->findOrFail($id);

    return view('venta/ticket', compact('venta'));
}
}
