<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7fafc;
            color: #4a5568;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
            flex-direction: column;
        }
        .container {
            padding: 20px;
        }
        h1 {
            font-size: 5rem;
            font-weight: bold;
            color: #e53e3e; /* Rojo para errores */
            margin-bottom: 0.5rem;
        }
        h2 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: #4299e1; /* Azul */
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            text-decoration: none;
            margin: 0.5rem;
            transition: background-color 0.15s ease-in-out;
        }
        .btn:hover {
            background-color: #2b6cb0; /* Azul más oscuro */
        }
        .btn-secondary {
            background-color: #6c757d; /* Gris */
        }
        .btn-secondary:hover {
            background-color: #545b62; /* Gris más oscuro */
        }
    </style>
    <!-- Si usas Google Fonts, puedes incluirlo aquí, ejemplo: -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <h2>Acceso Denegado</h2>
        <p>
            Lo sentimos, no tienes permiso para acceder a esta página.
        </p>
        @if(url()->previous() !== url()->current())
            <a href="{{ url()->previous() }}" class="btn">Volver a la página anterior</a>
        @endif
        {{-- Asumiendo que 'dashboard' es una ruta que siempre existe y es accesible --}}
        {{-- Si no, puedes cambiarla a la URL base '/' o a una ruta de login --}}
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al Inicio</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary">Ir al Login</a>
        @endauth
    </div>
</body>
</html>
