@extends('layout')

@section('content')
    <div class="container">
        <div>
            <a href="{{ url('usuarios') }}" class="btn btn-secondary float-end mt-2">Volver</a>
            <h1>Editar Usuario</h1>
        </div>
        <hr>

        <form id="formUsuario" action="{{ route('usuarios.update', $usuario->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre', $usuario->nombre) }}">
                    @error('nombre') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="documento" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="documento" name="documento" required value="{{ old('documento', $usuario->documento) }}">
                    @error('documento') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', $usuario->email) }}">
                    @error('email') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <input type="text" class="form-control" id="rol" name="rol" required value="{{ old('rol', $usuario->rol) }}">
                    @error('rol') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @error('password') <div class="error text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            <p class="text-muted">Deje los campos de contraseña en blanco si no desea cambiarla.</p>

            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/localization/messages_es.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#formUsuario").validate({
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
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    rol: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    password: {
                        minlength: 8, // Opcional, pero si se ingresa, debe tener minlength
                    },
                    password_confirmation: {
                        minlength: 8,
                        equalTo: "#password" // Solo si se ingresa password
                    }
                },
                messages: {
                    password_confirmation: {
                        equalTo: "Las contraseñas no coinciden."
                    }
                }
            });
        });
    </script>
@endsection
