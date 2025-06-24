<!DOCTYPE html>
<html>
<head>
    <title>Administrar Permisos</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { display: flex; }
        .user-list { width: 30%; border-right: 1px solid #ccc; padding-right: 20px; }
        .permission-list { width: 70%; padding-left: 20px; }
        .user-item { padding: 8px; cursor: pointer; border-bottom: 1px solid #eee; }
        .user-item:hover, .user-item.active { background-color: #f0f0f0; }
        .action-item { margin-bottom: 5px; }
        .action-item label { margin-left: 5px; }
        .save-button { margin-top: 15px; padding: 10px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        .save-button:hover { background-color: #218838; }
        h1, h2, h3 { color: #333; }
    </style>
</head>
<body>
    <h1>Administrar Permisos</h1>

    <div class="container">
        <div class="user-list">
            <h2>Usuarios</h2>
            @if($users->isEmpty())
                <p>No hay usuarios.</p>
            @else
                <ul id="users">
                    @foreach($users as $user)
                        <li class="user-item" data-id="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="permission-list">
            <h2>Permisos para <span id="selectedUserName">Nadie</span></h2>
            @if($acciones->isEmpty())
                <p>No hay acciones definidas.</p>
            @else
                <form id="permissionsForm" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <h3>Acciones Disponibles</h3>
                    @foreach($acciones as $accion)
                        <div class="action-item">
                            <input type="checkbox" id="accion_{{ $accion->id }}" name="acciones_permitidas[]" value="{{ $accion->id }}">
                            <label for="accion_{{ $accion->id }}">{{ $accion->nombre }} ({{ $accion->ruta }})</label>
                        </div>
                    @endforeach
                    <button type="submit" class="save-button">Guardar Permisos</button>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userItems = document.querySelectorAll('.user-item');
            const permissionsForm = document.getElementById('permissionsForm');
            const userIdInput = document.getElementById('user_id');
            const selectedUserNameSpan = document.getElementById('selectedUserName');
            const checkboxes = permissionsForm.querySelectorAll('input[type="checkbox"]');

            userItems.forEach(item => {
                item.addEventListener('click', function () {
                    userItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    const userId = this.dataset.id;
                    const userName = this.textContent.split(' (')[0];
                    userIdInput.value = userId;
                    selectedUserNameSpan.textContent = userName;
                    permissionsForm.action = `/permisos/${userId}`; // Actualizar la acción del formulario

                    // Resetear checkboxes
                    checkboxes.forEach(checkbox => checkbox.checked = false);

                    // Cargar permisos del usuario seleccionado
                    fetch(`/permisos/${userId}/acciones`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.acciones_usuario) {
                                data.acciones_usuario.forEach(accionId => {
                                    const checkbox = document.getElementById(`accion_${accionId}`);
                                    if (checkbox) {
                                        checkbox.checked = true;
                                    }
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching user permissions:', error));
                });
            });
        });
    </script>
</body>
</html>
