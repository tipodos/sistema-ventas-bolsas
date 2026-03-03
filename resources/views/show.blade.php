@extends('layouts.template')

@section('title', 'Detalles de Venta')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard del Negocio</h1>
            <span class="badge bg-dark px-3 py-2">{{ date('d/m/Y') }}</span>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 border-start border-primary border-5 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas de Hoy</div>
                        <div class="h5 mb-0 font-weight-bold">S/ {{ number_format($totalVentasHoy, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 border-start border-success border-5 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventas del Mes</div>
                        <div class="h5 mb-0 font-weight-bold">S/ {{ number_format($totalVentasMes, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 border-start border-info border-5 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Yape / Plin (Hoy)</div>
                        <div class="h5 mb-0 font-weight-bold">S/ {{ number_format($ventasYape, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 border-start border-danger border-5 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stock Crítico</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $conteoCriticos }} Alertas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fire me-2"></i>Productos Estrella
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach ($topProductos as $tp)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold">{{ $tp->producto->nombre }}</span>
                                    <span class="small text-muted">{{ $tp->total_vendido }} vendidos</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ ($tp->total_vendido / $topProductos->first()->total_vendido) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-exclamation-circle me-2"></i>Reponer
                            Urgente</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($productosCriticos as $pc)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $pc->nombre }}
                                    <span class="badge bg-danger rounded-pill">{{ $pc->stock }} uni.</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Todo el stock está bien.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
