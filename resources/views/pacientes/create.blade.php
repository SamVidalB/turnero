@extends('layout')

@section('content')
    <div class="container">
        <div>
            <a href="{{ url('pacientes') }}" class="btn btn-secondary float-end mt-2">Volver</a>
            <h1>Nuevo Paciente</h1>
        </div>
        <hr>

        <form id="formPaciente" action="{{ route('pacientes.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required autofocus value="{{ old('nombre') }}">
                    @error('nombre') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="documento" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="documento" name="documento" required value="{{ old('documento') }}">
                    @error('documento') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="asegurador_id" class="form-label">Asegurador</label>
                    <select class="form-select" id="asegurador_id" name="asegurador_id" required>
                        <option value="">Seleccione un asegurador</option>
                        @foreach($aseguradores as $asegurador)
                            <option value="{{ $asegurador->id }}" {{ old('asegurador_id') == $asegurador->id ? 'selected' : '' }}>
                                {{ $asegurador->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('asegurador_id') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Crear Paciente</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/localization/messages_es.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#formPaciente").validate({
                rules: {
                    nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    documento: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    },
                    fecha_nacimiento: {
                        required: true,
                        date: true
                    },
                    asegurador_id: {
                        required: true
                    },
                    categoria_id: {
                        required: true
                    }
                },
                messages: {
                    // Puedes personalizar mensajes aquí si es necesario
                }
            });
        });
    </script>
@endsection
