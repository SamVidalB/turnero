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
                dom: 'lBfrtip',
                buttons: [
                    { extend: 'copy', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'excel', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'pdf', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'print', exportOptions: { columns: ':not(:last-child)' } }
                ]
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
                                <button type="submit" class="btn btn-icon btn-outline-primary" title="Eliminar" onclick="return confirm('¿Está seguro de que desea eliminar esta sede?');">
                                    <span class="icon-base bx bx-trash icon-md"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
