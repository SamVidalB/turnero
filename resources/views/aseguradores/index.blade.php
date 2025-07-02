@extends('layout')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css">
    <style>
        th{
            text-align: center!important;
        }
        td{
            text-align: left!important;
        }
    </style>
@stop

@section('scripts')

    <script src="//cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="//cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>
    

    <script>
        $(document).ready(function() {
            $('#tabla').DataTable({  // Usa `DataTable()` en lugar de `dataTable()`
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

        <div>
            @if(auth()->user()->hasAccionRuta('aseguradores.trash'))
                <a href="{{ url('aseguradores/trash') }}" class="btn btn-danger float-end mt-2 ms-2">Papelera</a>
            @endif
            
            @if(auth()->user()->hasAccionRuta('aseguradores.create'))
                <a href="{{ url('aseguradores/create') }}" class="btn btn-primary float-end mt-2">Crear Asegurador</a>
            @endif
        </div>
            <h1>Aseguradores</h1>
        </div>
        
        <table class="table table-striped table-bordered" id="tabla">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>NIT</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $asegurador)
                    <tr>
                        <!-- <td>{{ $asegurador->id }}</td> -->
                        <td>{{ $asegurador->nit }}</td>
                        <td>{{ $asegurador->nombre }}</td>
                        <td>
                            @if(auth()->user()->hasAccionRuta('aseguradores.edit'))
                                <a href="{{ url('aseguradores/' . $asegurador->id . '/edit') }}" class="btn btn-icon btn-outline-primary" title="Editar">
                                    <span class="icon-base bx bx-edit icon-md"></span>
                                </a>
                            @else
                                <button class="btn btn-icon btn-outline-primary" title="Editar" disabled>
                                    <span class="icon-base bx bx-edit icon-md"></span>
                                </button>
                            @endif

                            @if(auth()->user()->hasAccionRuta('aseguradores.destroy'))
                                <form action="{{ url('aseguradores/' . $asegurador->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-outline-primary" title="Eliminar" onclick="return confirm('¿Está seguro de que desea eliminar este asegurador?');">
                                        <span class="icon-base bx bx-trash icon-md"></span>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-icon btn-outline-primary" title="Eliminar" disabled>
                                    <span class="icon-base bx bx-trash icon-md"></span>
                                </button>
                            @endif
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div> 

@endsection
