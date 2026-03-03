<!DOCTYPE html>
<html>
<head>
    <title>Ticket #{{ $venta->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: 0; padding: 10px; }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; font-size: 12px; }
    </style>
</head>
<body onload="window.print()"> <div class="text-center">
        <h3>MI NEGOCIO</h3>
        <p>Ticket: #{{ $venta->id }}</p>
        <p>Fecha: {{ $venta->created_at }}</p>
    </div>
    <div class="divider"></div>
    <table>
        @foreach($venta->detalles as $detalle)
        <tr>
            <td>{{ $detalle->cantidad }} x {{ $detalle->producto->nombre }}</td>
            <td style="text-align: right;">S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
        </tr>
        @endforeach
    </table>
    <div class="divider"></div>
    <h4 style="text-align: right;">TOTAL: S/ {{ number_format($venta->total, 2) }}</h4>
    <div class="text-center">
        <p>Atendido por: {{ $venta->personal->name }}</p>
        <p>¡Gracias por su compra!</p>
    </div>
</body>
</html>