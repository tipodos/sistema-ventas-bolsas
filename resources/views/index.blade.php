@extends('layouts/template')

@section('title', 'Terminal de Ventas - Plastiquería')

@section('content')
    <div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 90vh;">
        <div class="row g-4">

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold text-dark mb-0">📦 Catálogo de Productos</h4>
                            <span class="badge bg-soft-primary text-primary px-3 py-2"
                                style="background: #e7f1ff; border-radius: 10px;">
                                {{ count($productos) }} ítems disponibles
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="search-box mb-4">
                            <div class="input-group input-group-lg shadow-sm"
                                style="border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text bg-white border-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" id="buscarProducto" class="form-control border-0 fs-5"
                                    placeholder="Buscar por nombre o categoría..." style="box-shadow: none;">
                            </div>
                        </div>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table align-middle">
                                <thead class="sticky-top bg-white" style="z-index: 10;">
                                    <tr class="text-muted small uppercase">
                                        <th class="border-0">DESCRIPCIÓN</th>
                                        <th class="border-0 text-center">PRECIO</th>
                                        <th class="border-0 text-center">STOCK</th>
                                        <th class="border-0 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProductos">
                                    @foreach ($productos as $p)
                                        <tr class="product-row">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-light rounded-3 me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 45px; height: 45px;">
                                                        <i class="fas fa-box text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $p->nombre }}</div>
                                                        <span
                                                            class="badge bg-light text-muted fw-normal">{{ $p->categoria }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold fs-5 text-dark">S/
                                                {{ number_format($p->precio, 2) }}</td>
                                            <td class="text-center">
                                                <div class="progress"
                                                    style="height: 6px; width: 60px; margin: 0 auto 5px auto;">
                                                    <div class="progress-bar {{ $p->stock <= 10 ? 'bg-danger' : 'bg-success' }}"
                                                        style="width: {{ ($p->stock / 100) * 100 }}%"></div>
                                                </div>
                                                <span
                                                    class="small fw-bold {{ $p->stock <= 10 ? 'text-danger' : 'text-muted' }}">
                                                    {{ $p->stock }} u.
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-primary btn-sm rounded-3 shadow-sm btn-agregar px-3"
                                                    data-id="{{ $p->id }}" data-nombre="{{ $p->nombre }}"
                                                    data-precio="{{ $p->precio }}" data-stock="{{ $p->stock }}">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; background: #fff;">
                    <div class="card-header bg-dark text-white py-4" style="border-radius: 20px 20px 0 0;">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-bag me-2"></i> Mi Carrito</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-2">PAGO</label>
                                <select id="metodoPago" class="form-select border-0 bg-light rounded-3">
                                    <option value="efectivo">💵 Efectivo</option>
                                    <option value="yape">📱 Yape / Plin</option>
                                    <option value="tarjeta">💳 Tarjeta</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-2">VENDEDOR</label>
                                <select id="personal_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">¿Quién eres?</option>
                                    @foreach ($personales as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="carritoItems" class="mb-4"
                            style="min-height: 200px; max-height: 350px; overflow-y: auto;">
                            <div class="text-center text-muted mt-5" id="carritoVacio">
                                <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                                <p>El carrito está vacío</p>
                            </div>
                        </div>

                        <div class="bg-light p-4 rounded-4 shadow-inner mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted fw-bold">SUBTOTAL</span>
                                <span class="text-muted fw-bold" id="subtotalLabel">S/ 0.00</span>
                            </div>
                            <hr class="my-2 opacity-50">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="fw-black mb-0">TOTAL</h3>
                                <h2 class="fw-black text-primary mb-0">S/ <span id="totalVenta">0.00</span></h2>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button class="btn btn-primary btn-lg py-3 fw-bold shadow ripple" id="btnFinalizar"
                                style="border-radius: 12px;">
                                <i class="fas fa-rocket me-2"></i> FINALIZAR Y COBRAR
                            </button>
                            <button class="btn btn-outline-danger btn-sm border-0" onclick="limpiarCarrito()">
                                <i class="fas fa-trash-alt me-1"></i> Cancelar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Estilos Piolas */
        .product-row {
            transition: all 0.2s ease;
        }

        .product-row:hover {
            background-color: #f8f9ff;
            transform: translateX(5px);
        }

        .bg-soft-primary {
            background: #eef4ff;
            color: #3b82f6;
        }

        .fw-black {
            font-weight: 900;
        }

        #carritoItems::-webkit-scrollbar {
            width: 4px;
        }

        #carritoItems::-webkit-scrollbar-thumb {
            background: #eee;
            border-radius: 10px;
        }

        .ripple {
            position: relative;
            overflow: hidden;
        }

        /* Efecto de fila de carrito */
        .item-carrito {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 12px 0;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // --- Lógica del Carrito Mejorada ---
        let carrito = [];
        let total = 0;

        // Buscador instantáneo
        document.getElementById('buscarProducto').addEventListener('input', function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('#tablaProductos tr');
            filas.forEach(f => f.style.display = f.innerText.toLowerCase().includes(filtro) ? '' : 'none');
        });

        // Agregar al carrito
        document.addEventListener('click', function(e) {
            let btn = e.target.closest('.btn-agregar');
            if (btn) {
                let p = {
                    id: btn.dataset.id,
                    nombre: btn.dataset.nombre,
                    precio: parseFloat(btn.dataset.precio),
                    stock: parseInt(btn.dataset.stock)
                };
                agregarAlCarrito(p);
            }
        });

        function agregarAlCarrito(p) {
            let item = carrito.find(x => x.id === p.id);
            if (p.stock <= 0) return Swal.fire('Sin Stock', 'No queda nada en estante', 'error');

            if (item) {
                if (item.cantidad < p.stock) item.cantidad++;
                else return Swal.fire('Tope de Stock', 'No puedes vender más de lo que hay', 'warning');
            } else {
                carrito.push({
                    ...p,
                    cantidad: 1
                });
            }
            renderizar();
        }

        function renderizar() {
            const contenedor = document.getElementById('carritoItems');
            const totalSpan = document.getElementById('totalVenta');

            // Si por alguna razón no encuentra el contenedor, salimos para no romper el código
            if (!contenedor) return;

            // Limpiamos el contenedor antes de volver a dibujar
            contenedor.innerHTML = '';
            total = 0;

            if (carrito.length === 0) {
                contenedor.innerHTML = `
            <div class="text-center text-muted mt-5" id="carritoVacio">
                <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                <p>El carrito está vacío</p>
            </div>`;
                totalSpan.innerText = '0.00';
                return;
            }

            // Dibujamos cada item
            carrito.forEach((item, i) => {
                let sub = item.precio * item.cantidad;
                total += sub;

                // Creamos un DIV para cada producto
                const div = document.createElement('div');
                div.className = "item-carrito d-flex align-items-center justify-content-between border-bottom py-2";
                div.innerHTML = `
            <div style="width: 60%">
                <div class="fw-bold text-dark" style="font-size: 0.9rem;">${item.nombre}</div>
                <small class="text-muted">S/ ${item.precio.toFixed(2)} x ${item.cantidad}</small>
            </div>
            <div class="text-end" style="width: 30%">
                <div class="fw-bold text-primary">S/ ${sub.toFixed(2)}</div>
            </div>
            <div style="width: 10%" class="text-end">
                <button class="btn btn-sm text-danger p-0" onclick="quitar(${i})">
                    <i class="fas fa-times-circle fa-lg"></i>
                </button>
            </div>
        `;
                contenedor.appendChild(div);
            });

            // Actualizamos el total al final
            totalSpan.innerText = total.toFixed(2);
        }

        function quitar(i) {
            carrito.splice(i, 1);
            renderizar();
        }

        // El Finalizar Venta que ya tenías (pero con bloqueo de botón)
        document.getElementById('btnFinalizar').onclick = function() {
            const btn = this;
            const personalId = document.getElementById('personal_id').value;
            const metodoPago = document.getElementById('metodoPago').value;

            if (carrito.length === 0 || !personalId) {
                return Swal.fire('Atención', 'Carrito vacío o falta vendedor', 'warning');
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';

            fetch("{{ route('ventas.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        productos: carrito,
                        personal_id: personalId,
                        metodo_pago: metodoPago,
                        total: total
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                                title: '¡Venta Exitosa!',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Imprimir Ticket'
                            })
                            .then(res => {
                                window.open(`/ventas/ticket/${data.venta_id}`, '_blank');
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-rocket me-2"></i> FINALIZAR Y COBRAR';
                    }
                });
        };

        function limpiarCarrito() {
            if (confirm('¿Limpiar todo el pedido?')) location.reload();
        }
    </script>
@endpush
