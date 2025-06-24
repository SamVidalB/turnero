@extends('layout')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
    <script>
        $(document).ready(function() {
            @if($data->count() > 0)
                $('#tablaSedesTrash').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json"
                    }
                });
            @endif
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
        @if (session('error'))
            <div class="alert alert-danger mt-2">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <a href="{{ url('sedes') }}" class="btn btn-primary float-end mt-2">Volver a Sedes</a>
            <h1>Papelera de Sedes</h1>
        </div>

        <table class="table table-striped table-bordered" id="tablaSedesTrash">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($data->count() > 0)
                    @foreach($data as $sede)
                        <tr>
                            <td>{{ $sede->nombre }}</td>
                            <td>{{ $sede->direccion }}</td>
                            <td>{{ $sede->municipio }}</td>
                            <td>
                                <form action="{{ url('sedes/' . $sede->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE') {{-- El método destroy maneja la restauración --}}
                                    <button type="submit" class="btn btn-icon btn-outline-primary" title="Restaurar" onclick="return confirm('¿Está seguro de que desea restaurar esta sede?');">
                                        <span class="icon-base bx bx-undo icon-md"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">No hay sedes en la papelera.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
