@extends('layout')

@section('content')

    <div class="container">
        <div>
            <a href="{{ url('aseguradores') }}" class="btn btn-secondary float-end mt-2">Volver</a>
            <h1>Nuevo Asegurador</h1>
        </div>
        <hr>
        
        <form id="form" action="{{ route('aseguradores.store') }}" method="post">

            @csrf

            <div class="col-6 mb-3">
                <label for="nit" class="form-label">NIT</label>
                <input type="number" class="form-control" id="nit" name="nit" required autofocus value="{{ old('nit') }}">
                @error('nit')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre') }}">
                @error('nombre')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Crear Asegurador</button>
        </form>

    </div> 
      
@endsection

@section('scripts')
    
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/localization/messages_es.min.js') }}"></script>
    
    <script>

        $( "#form" ).validate({
            rules: {
                nit: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    // maxlength: 20
                },
                nombre: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                }
            }
        });
        
    </script>

@endsection