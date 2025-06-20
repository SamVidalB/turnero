@extends('layout')

@section('content')

    <div class="container">

        @if (session('message'))
            <div class="alert alert-{{ session('type') }} mt-2">
                {{ session('message') }}
            </div>
        @endif

        <div>
            <a href="{{ url('aseguradores/trash') }}" class="btn btn-danger float-end mt-2 ms-2">Papelera</a>
            <a href="{{ url('aseguradores') }}" class="btn btn-primary float-end mt-2">Volver</a>
            <h1>Papelera</h1>
        </div>
        
        <table class="table table-striped">
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

                            <form action="{{ url('aseguradores/' . $asegurador->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-outline-primary" title="Recuperar" onclick="return confirm('¿Está seguro de que desea recuperar este asegurador?');">
                                    <span class="icon-base bx bx-undo icon-md"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div> 

@endsection
