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

        <hr class="my-4">

        {{-- Sección de Permisos --}}
        <div class="card mt-4">
            <h5 class="card-header">Gestionar Permisos</h5>
            <div class="card-body">
                <form action="{{ route('usuarios.updatePermissions', $usuario->id) }}" method="POST">
                    @csrf
                    {{-- Aunque la ruta es POST, algunos prefieren especificar @method('POST') explícitamente --}}

                    @if(isset($accionesDisponibles) && $accionesDisponibles->count() > 0)
                        @php $currentModulo = ''; @endphp
                        @foreach($accionesDisponibles as $accion)
                            @if($accion->modulo !== $currentModulo)
                                @if($currentModulo !== '')
                                    </div> {{-- Cierra el div del grupo anterior de checkboxes --}}
                                @endif
                                <h6 class="mt-3">{{ $accion->modulo ?: 'General' }}</h6>
                                <div class="list-group list-group-flush"> {{-- Abre un div para el nuevo grupo de checkboxes --}}
                                @php $currentModulo = $accion->modulo; @endphp
                            @endif
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="acciones_ids[]" value="{{ $accion->id }}" id="accion_{{ $accion->id }}"
                                       {{ (isset($accionesAsignadasIds) && in_array($accion->id, $accionesAsignadasIds)) ? 'checked' : '' }}>
                                {{ $accion->nombre }}
                                <small class="text-muted">({{ $accion->ruta }})</small>
                            </label>
                            @if($loop->last && $currentModulo !== '')
                                </div> {{-- Cierra el último div del grupo de checkboxes --}}
                            @endif
                        @endforeach
                    @else
                        <p>No hay acciones disponibles para asignar.</p>
                    @endif

                    <button type="submit" class="btn btn-primary mt-3">Guardar Permisos</button>
                </form>
            </div>
        </div>
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
