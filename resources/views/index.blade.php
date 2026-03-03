@extends('layouts/template')

@section('title', 'Ventas')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Productos Disponibles</h5>
                        <span class="badge bg-light text-primary">{{ count($productos) }} items</span>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" id="buscarProducto" class="form-control border-start-0"
                                placeholder="Escribe el nombre del producto...">
                        </div>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProductos">
                                    @foreach ($productos as $p)
                                        <tr>
                                            <td><strong>{{ $p->nombre }}</strong></td>
                                            <td>S/ {{ number_format($p->precio, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $p->stock <= 10 ? 'bg-warning text-dark' : 'bg-info text-dark' }}">
                                                    {{ $p->stock }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-success btn-sm rounded-pill btn-agregar"
                                                    data-id="{{ $p->id }}" data-nombre="{{ $p->nombre }}"
                                                    data-precio="{{ $p->precio }}" data-stock="{{ $p->stock }}">
                                                    <i class="fas fa-cart-plus"></i> Agregar
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

            <div class="col-md-5">
                <div class="card shadow border-0" style="position: sticky; top: 20px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Detalle de Venta</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Producto</th>
                                    <th>Cant.</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="carritoBody">
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary" >Método de Pago:</label>
                            <select id="metodoPago" class="form-select form-select-lg">
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="yape">📱 Yape / Plin</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">VENDEDOR ACTUAL:</label>
                            <select id="personal_id" class="form-select">
                                
                                <option value="">-- ¿Quién atiende? --</option>
                                @foreach ($personales as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-4 bg-light">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="mb-0">TOTAL: S/ <span id="totalVenta">0.00</span></h4>
                            </div>
                            <button class="btn btn-primary w-100 btn-lg shadow-sm fw-bold mt-3" id="btnFinalizar">
                                <i class="fas fa-print me-2"></i> FINALIZAR Y COBRAR
                            </button>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-3" onclick="limpiarCarrito()">
                                <i class="fas fa-trash"></i> Cancelar Pedido
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
        #tablaProductos tr {
            cursor: pointer;
            transition: 0.3s;
        }

        #tablaProductos tr:hover {
            background-color: #f8f9fa;
        }

        .table-responsive::-webkit-scrollbar {
            width: 6px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // 1. Buscador en tiempo real
document.getElementById('buscarProducto').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaProductos tr');
    filas.forEach(fila => {
        let nombreProducto = fila.querySelector('td').innerText.toLowerCase();
        fila.style.display = nombreProducto.includes(filtro) ? '' : 'none';
    });
});

let carrito = [];
let total = 0;

// 2. Agregar al carrito
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-agregar') || event.target.closest('.btn-agregar')) {
        const boton = event.target.classList.contains('btn-agregar') ? event.target : event.target.closest('.btn-agregar');
        const id = boton.getAttribute('data-id');
        const nombre = boton.getAttribute('data-nombre');
        const precio = parseFloat(boton.getAttribute('data-precio'));
        const stock = parseInt(boton.getAttribute('data-stock'));
        agregarAlCarrito(id, nombre, precio, stock);
    }
});

function agregarAlCarrito(id, nombre, precio, stockActual) {
    let item = carrito.find(p => p.id === id);
    if (stockActual <= 0) {
        return Swal.fire('¡Sin Stock!', 'No queda ' + nombre, 'error');
    }
    if (item) {
        if (item.cantidad < stockActual) { item.cantidad++; } 
        else { return Swal.fire('Límite', 'No hay más stock', 'warning'); }
    } else {
        carrito.push({ id, nombre, precio, cantidad: 1 });
    }
    renderizarTabla();
}

function renderizarTabla() {
    const tbody = document.getElementById('carritoBody');
    const totalSpan = document.getElementById('totalVenta');
    if (!tbody) return;
    tbody.innerHTML = '';
    total = 0;
    carrito.forEach((item, index) => {
        let subtotal = item.precio * item.cantidad;
        total += subtotal;
        tbody.innerHTML += `
            <tr>
                <td><small>${item.nombre}</small></td>
                <td>${item.cantidad}</td>
                <td>S/ ${subtotal.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm" onclick="quitar(${index})">x</button></td>
            </tr>`;
    });
    totalSpan.innerText = total.toFixed(2);
}

function quitar(index) {
    carrito.splice(index, 1);
    renderizarTabla();
}

// 3. FINALIZAR VENTA (ESTA ES LA VERSIÓN ÚNICA Y CORREGIDA)
document.getElementById('btnFinalizar').onclick = function() {
    const personalId = document.getElementById('personal_id').value;
    const metodoPago = document.getElementById('metodoPago').value;

    if (carrito.length === 0) {
        return Swal.fire('Mano', 'El carrito está vacío', 'warning');
    }
    if (!personalId) {
        return Swal.fire('Atención', 'Selecciona quién está atendiendo', 'warning');
    }

    fetch("{{ route('ventas.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            productos: carrito, // <--- AHORA SÍ COINCIDE CON TU CONTROLADOR
            personal_id: personalId,
            metodo_pago: metodoPago,
            total: total
        })
    })
    .then(response => response.json())
    .then(data => {
    if (data.success) {
        Swal.fire({
            title: '✅ ¡Venta realizada!',
            text: '¿Deseas imprimir el ticket?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Sí, imprimir',
            cancelButtonText: 'No, solo cerrar'
        }).then((result) => {
            // Abrimos el ticket en una pestaña nueva usando el venta_id que devuelve el controlador
            window.open(`/ventas/ticket/${data.venta_id}`, '_blank');
            
            // Recargamos la página principal
            location.reload();
        });
    } else {
        Swal.fire('Error', data.message, 'error');
    }
})
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', "Hubo un problema al conectar con el servidor", 'error');
    });
};

function limpiarCarrito() {
    if (confirm("¿Seguro que quieres borrar todo?")) {
        location.reload();
    }
}
    </script>
@endpush
