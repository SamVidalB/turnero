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

            <button type="submit" class="btn btn-primary">Actualizar Sede</button>
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
