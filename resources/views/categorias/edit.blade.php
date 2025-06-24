@extends('layout')

@section('content')
    <div class="container">
        <div>
            <a href="{{ url('categorias') }}" class="btn btn-secondary float-end mt-2">Volver</a>
            <h1>Editar Categoría</h1>
        </div>
        <hr>

        <form id="formCategoria" action="{{ route('categorias.update', $categoria->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre de la Categoría</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required autofocus value="{{ old('nombre', $categoria->nombre) }}">
                @error('nombre') <div class="error text-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/localization/messages_es.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#formCategoria").validate({
                rules: {
                    nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    }
                },
                messages: {
                    nombre: {
                        required: "Por favor, ingrese el nombre de la categoría.",
                        minlength: "El nombre debe tener al menos 3 caracteres.",
                        maxlength: "El nombre no puede exceder los 255 caracteres."
                    }
                }
            });
        });
    </script>
@endsection
