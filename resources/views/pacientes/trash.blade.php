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
            $('#tablaPacientesTrash').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json"
                }
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
            <a href="{{ url('pacientes') }}" class="btn btn-primary float-end mt-2">Volver a Pacientes</a>
            <h1>Papelera de Pacientes</h1>
        </div>

        <table class="table table-striped table-bordered" id="tablaPacientesTrash">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Fecha Nacimiento</th>
                    <th>Asegurador</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($data->count() > 0)
                    @foreach($data as $paciente)
                        <tr>
                            <td>{{ $paciente->nombre }}</td>
                            <td>{{ $paciente->documento }}</td>
                            <td>{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>{{ $paciente->asegurador->nombre ?? 'N/A' }}</td>
                            <td>{{ $paciente->categoria->nombre ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ url('pacientes/' . $paciente->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE') {{-- El método destroy maneja la restauración --}}
                                    <button type="submit" class="btn btn-icon btn-outline-primary" title="Restaurar" onclick="return confirm('¿Está seguro de que desea restaurar este paciente?');">
                                        <span class="icon-base bx bx-undo icon-md"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No hay pacientes en la papelera.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
