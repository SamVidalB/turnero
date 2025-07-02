@extends('layout')

@section('content')
    <div class="container">
        <div>
            <a href="{{ url('sedes') }}" class="btn btn-secondary float-end mt-2">Volver</a>
            <h1>Editar Sede</h1>
        </div>
        <hr>

        <form id="formSede" action="{{ route('sedes.update', $sede->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre de la Sede</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required autofocus value="{{ old('nombre', $sede->nombre) }}">
                    @error('nombre') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                 <div class="col-md-6 mb-3">
                    <label for="municipio" class="form-label">Municipio</label>
                    <input type="text" class="form-control" id="municipio" name="municipio" value="{{ old('municipio', $sede->municipio) }}">
                    @error('municipio') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $sede->direccion) }}">
                @error('direccion') <div class="error text-danger">{{ $message }}</div> @enderror
            </div>

            <hr>
            <h5>Puntos de Atención Actuales (Solo Informativo)</h5>
            @if($sede->puntosAtencion && $sede->puntosAtencion->count() > 0)
                <ul>
                    @foreach($sede->puntosAtencion->groupBy(function($item) { return explode('-', $item->nombre)[0]; }) as $tipo => $puntos)
                        <li><strong>{{ $tipo }}:</strong> {{ $puntos->count() }} punto(s) ({{ $puntos->pluck('nombre')->implode(', ') }})</li>
                    @endforeach
                </ul>
            @else
                <p>Esta sede no tiene puntos de atención asociados actualmente.</p>
            @endif

            <hr>
            <h5>Configurar Nuevos Puntos de Atención (los existentes serán reemplazados)</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="cantidad_admision" class="form-label">Cantidad Puntos de Admisión</label>
                    <input type="number" class="form-control" id="cantidad_admision" name="cantidad_admision" value="{{ old('cantidad_admision', 0) }}" min="0">
                    @error('cantidad_admision') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cantidad_consulta" class="form-label">Cantidad Puntos de Consulta</label>
                    <input type="number" class="form-control" id="cantidad_consulta" name="cantidad_consulta" value="{{ old('cantidad_consulta', 0) }}" min="0">
                    @error('cantidad_consulta') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cantidad_postconsulta" class="form-label">Cantidad Puntos de Postconsulta</label>
                    <input type="number" class="form-control" id="cantidad_postconsulta" name="cantidad_postconsulta" value="{{ old('cantidad_postconsulta', 0) }}" min="0">
                    @error('cantidad_postconsulta') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Sede y Puntos de Atención</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/localization/messages_es.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#formSede").validate({
                rules: {
                    nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    direccion: {
                        maxlength: 255
                    },
                    municipio: {
                        maxlength: 100
                    },
                    cantidad_admision: {
                        digits: true,
                        min: 0
                    },
                    cantidad_consulta: {
                        digits: true,
                        min: 0
                    },
                    cantidad_postconsulta: {
                        digits: true,
                        min: 0
                    }
                },
                messages: {
                    nombre: {
                        required: "Por favor, ingrese el nombre de la sede.",
                        minlength: "El nombre debe tener al menos 3 caracteres.",
                        maxlength: "El nombre no puede exceder los 255 caracteres."
                    }
                }
            });
        });
    </script>
@endsection
