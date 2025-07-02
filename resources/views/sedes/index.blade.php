@extends('layout')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css">
    <style>
        th {
            text-align: center !important;
        }
        td {
            text-align: left !important;
        }
    </style>
@stop

@section('scripts')
    <script src="//cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="//cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablaSedes').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json"
                },
                // dom: 'lBfrtip',
                // buttons: [
                //     { extend: 'copy', exportOptions: { columns: ':not(:last-child)' } },
                //     { extend: 'excel', exportOptions: { columns: ':not(:last-child)' } },
                //     { extend: 'pdf', exportOptions: { columns: ':not(:last-child)' } },
                //     { extend: 'print', exportOptions: { columns: ':not(:last-child)' } }
                // ]
            });

            // Lógica para la modal de detalles de sede
            const sedeDetallesModal = document.getElementById('sedeDetallesModal');
            const modalSedeNombre = document.getElementById('modalSedeNombre');
            const modalSedeDireccion = document.getElementById('modalSedeDireccion');
            const modalSedeMunicipio = document.getElementById('modalSedeMunicipio');
            const modalSedePuntosAtencionLista = document.getElementById('modalSedePuntosAtencionLista');
            const modalSedeError = document.getElementById('modalSedeError');

            document.querySelectorAll('.btn-ver-detalles').forEach(button => {
                button.addEventListener('click', function() {
                    const sedeId = this.dataset.sedeId;

                    // Limpiar contenido anterior y ocultar error
                    modalSedeNombre.textContent = 'Cargando...';
                    modalSedeDireccion.textContent = 'Cargando...';
                    modalSedeMunicipio.textContent = 'Cargando...';
                    modalSedePuntosAtencionLista.innerHTML = '<li>Cargando...</li>';
                    modalSedeError.style.display = 'none';
                    modalSedeError.textContent = '';

                    fetch(`/sedes/${sedeId}/json`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error HTTP: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            modalSedeNombre.textContent = data.nombre || 'N/A';
                            modalSedeDireccion.textContent = data.direccion || 'N/A';
                            modalSedeMunicipio.textContent = data.municipio || 'N/A';

                            modalSedePuntosAtencionLista.innerHTML = ''; // Limpiar lista
                            if (data.puntos_atencion && data.puntos_atencion.length > 0) {
                                data.puntos_atencion.forEach(punto => {
                                    const li = document.createElement('li');
                                    li.textContent = punto.nombre;
                                    modalSedePuntosAtencionLista.appendChild(li);
                                });
                            } else {
                                modalSedePuntosAtencionLista.innerHTML = '<li>No hay puntos de atención asociados.</li>';
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar detalles de la sede:', error);
                            modalSedeNombre.textContent = 'Error';
                            modalSedeDireccion.textContent = 'Error';
                            modalSedeMunicipio.textContent = 'Error';
                            modalSedePuntosAtencionLista.innerHTML = '<li>Error al cargar puntos.</li>';
                            modalSedeError.textContent = 'No se pudieron cargar los detalles de la sede. Por favor, inténtelo de nuevo más tarde.';
                            modalSedeError.style.display = 'block';
                        });
                });
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        @if (session('message'))
            <div class="alert alert-{{ session('type') }} mt-2">
                {{ session('message') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mt-2">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <a href="{{ url('sedes/trash') }}" class="btn btn-danger float-end mt-2 ms-2">Papelera</a>
            <a href="{{ url('sedes/create') }}" class="btn btn-primary float-end mt-2">Crear Sede</a>
            <h1>Sedes</h1>
        </div>

        <table class="table table-striped table-bordered" id="tablaSedes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $sede)
                    <tr>
                        <td>{{ $sede->nombre }}</td>
                        <td>{{ $sede->direccion }}</td>
                        <td>{{ $sede->municipio }}</td>
                        <td>
                            <a href="{{ url('sedes/' . $sede->id . '/edit') }}" class="btn btn-icon btn-outline-primary" title="Editar">
                                <span class="icon-base bx bx-edit icon-md"></span>
                            </a>
                            <form action="{{ url('sedes/' . $sede->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de que desea eliminar esta sede?');">
                                    <span class="icon-base bx bx-trash icon-md"></span>
                                </button>
                            </form>
                            <button type="button" class="btn btn-icon btn-outline-info btn-ver-detalles" data-bs-toggle="modal" data-bs-target="#sedeDetallesModal" data-sede-id="{{ $sede->id }}" title="Ver Detalles">
                                <span class="icon-base bx bx-show icon-md"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para Detalles de Sede -->
    <div class="modal fade" id="sedeDetallesModal" tabindex="-1" aria-labelledby="sedeDetallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sedeDetallesModalLabel">Detalles de la Sede</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="modalSedeNombre"></span></p>
                    <p><strong>Dirección:</strong> <span id="modalSedeDireccion"></span></p>
                    <p><strong>Municipio:</strong> <span id="modalSedeMunicipio"></span></p>
                    <hr>
                    <h6>Puntos de Atención:</h6>
                    <ul id="modalSedePuntosAtencionLista">
                        <!-- Los puntos se cargarán aquí -->
                    </ul>
                    <div id="modalSedeError" class="alert alert-danger" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
