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
            $('#tablaUsuariosTrash').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json"
                }
                // No se necesitan botones de exportación en la papelera generalmente
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
        @if (session('error'))
            <div class="alert alert-danger mt-2">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <a href="{{ url('usuarios') }}" class="btn btn-primary float-end mt-2">Volver a Usuarios</a>
            <h1>Papelera de Usuarios</h1>
        </div>

        <table class="table table-striped table-bordered" id="tablaUsuariosTrash">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($data->count() > 0)
                    @foreach($data as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->documento }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->rol }}</td>
                            <td>
                                <form action="{{ url('usuarios/' . $usuario->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE') {{-- El método destroy maneja la restauración --}}
                                    <button type="submit" class="btn btn-icon btn-outline-primary" title="Restaurar" onclick="return confirm('¿Está seguro de que desea restaurar este usuario?');">
                                        <span class="icon-base bx bx-undo icon-md"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios en la papelera.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
