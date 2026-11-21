<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Creaciones y Fantasías Erlinda</title>
    
    <!-- CSS del Dashboard y CSS específico del módulo de usuarios -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>
<body>

    <!-- BARRA SUPERIOR -->
    <header class="topbar">
        <div class="topbar-marca">
            <span class="figura"></span>
            <span>CREACIONES Y FANTASÍAS ERLINDA</span>
        </div>
        <div class="topbar-usuario">
            <span class="usuario-nombre">
                <span>{{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-cerrar-sesion" title="Cerrar sesión">
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </header>

    <!-- MENÚ LATERAL -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <img src="{{ asset('images/ini.png') }}" alt="Inicio" style="width: 20px; height: 20px;">
            <span class="texto">Inicio</span>
        </a>
        <a href="{{ route('alquiler.seleccionar') }}" class="menu-item">
            <img src="{{ asset('images/resg.png') }}" alt="Registrar" style="width: 20px; height: 20px;">
            <span class="texto">Registrar alquiler</span>
        </a>
        <a href="{{ route('stock') }}" class="menu-item">
            <span class="icono">📦</span>
            <span class="texto">Stock</span>
        </a>
        <a href="{{ route('alquileres') }}" class="menu-item">
            <span class="icono">📋</span>
            <span class="texto">Alquileres</span>
        </a>
        @if(auth()->check() && auth()->user()->esAdmin())
        <a href="{{ route('reportes') }}" class="menu-item">
            <span class="icono">📈</span>
            <span class="texto">Reportes</span>
        </a>
        <a href="{{ route('usuarios.index') }}" class="menu-item activo">
            <span class="icono">👥</span>
            <span class="texto">Usuarios</span>
        </a>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px; padding: 0 20px;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 15px;">
                <span>🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido">
        <div class="gestion-wrapper">

            <!-- ALERTAS DE ÉXITO O ERROR -->
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    <div>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <h1 class="titulo-pagina">Gestión de Usuarios</h1>

            <!-- CONTENEDOR PRINCIPAL: SOLO LISTA DE USUARIOS -->
            <div class="card-gestion" style="width: 100%; box-sizing: border-box;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f3f5; flex-wrap: wrap; gap: 10px;">
                    <h2 class="card-titulo" style="margin-bottom: 0; padding-bottom: 0; border-bottom: none; font-size: 22px;">Lista de Usuarios</h2>
                    <button type="button" onclick="abrirModalCrear()" style="background: #2563eb; color: #ffffff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25); transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        <span>+ Agregar usuario</span>
                    </button>
                </div>

                <div class="tabla-container">
                        <table class="tabla-usuarios">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Creado</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Correo Electrónico</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $u)
                                    <tr>
                                        <td class="td-id">{{ $u->idUsuario }}</td>
                                        <td class="td-fecha">
                                            {{ $u->created_at ? $u->created_at->timezone('America/La_Paz')->format('Y-m-d H:i') : '2026-10-21 01:16' }}
                                        </td>
                                        <td class="td-nombre">
                                            {{ $u->nombre }} {{ $u->apellido }}
                                        </td>
                                        <td class="td-correo">
                                            {{ $u->email }}
                                        </td>
                                        <td class="td-rol">
                                            @if($u->idRol == 1 || strtolower(optional($u->rol)->nombre ?? '') === 'administrador')
                                                <span class="badge-rol badge-admin">Administrador</span>
                                            @else
                                                <span class="badge-rol badge-empleado">Empleado</span>
                                            @endif
                                        </td>
                                        <td class="td-acciones">
                                            <div class="acciones-flex">
                                                
                                                <!-- Botón Editar -->
                                                <button type="button" class="btn-accion-item editar"
                                                    onclick="abrirModalEditar({{ $u->idUsuario }}, '{{ addslashes($u->nombre) }}', '{{ addslashes($u->apellido) }}', '{{ addslashes($u->telefono ?? '') }}', '{{ addslashes($u->email) }}', {{ $u->idRol }})"
                                                    title="Editar">
                                                    <img src="{{ asset('img/editar.png') }}" alt="Editar">
                                                    <span class="accion-label">Editar</span>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <form id="form-eliminar-{{ $u->idUsuario }}" action="{{ route('usuarios.destroy', $u->idUsuario) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-accion-item eliminar"
                                                        onclick="confirmarEliminarUsuario({{ $u->idUsuario }}, '{{ addslashes($u->nombre . ' ' . $u->apellido) }}')"
                                                        title="Eliminar">
                                                        <img src="{{ asset('img/eliminar.jpeg') }}" alt="Eliminar">
                                                        <span class="accion-label">Eliminar</span>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 25px; color: #6b7280;">
                                            No hay usuarios registrados actualmente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

        </div>
    </main>

    <!-- MODAL PARA CREAR NUEVO USUARIO -->
    <div id="modalNuevoUsuario" class="modal-overlay {{ (isset($errors) && $errors->any()) ? 'activo' : '' }}">
        <div class="modal-contenedor">
            <div class="modal-header">
                <h3>Nuevo Usuario</h3>
                <button type="button" class="modal-close" onclick="cerrarModalCrear()">&times;</button>
            </div>
            
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="form-row-dos">
                        <div class="form-group">
                            <label for="nuevo_nombre">Nombre</label>
                            <input type="text" name="nombre" id="nuevo_nombre" class="form-control" placeholder="Nombre" value="{{ old('nombre') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nuevo_apellido">Apellido</label>
                            <input type="text" name="apellido" id="nuevo_apellido" class="form-control" placeholder="Apellido" value="{{ old('apellido') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nuevo_telefono">Teléfono</label>
                        <input type="text" name="telefono" id="nuevo_telefono" class="form-control" placeholder="Teléfono" value="{{ old('telefono') }}">
                    </div>

                    <div class="form-group">
                        <label for="nuevo_email">Correo Electrónico</label>
                        <input type="email" name="email" id="nuevo_email" class="form-control" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nuevo_password">Contraseña</label>
                        <div class="password-input-wrapper">
                            <input type="password" name="password" id="nuevo_password" class="form-control" placeholder="Contraseña" required>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('nuevo_password', this)" title="Mostrar/Ocultar contraseña">👁️</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nuevo_password_confirmation">Confirmar Contraseña</label>
                        <div class="password-input-wrapper">
                            <input type="password" name="password_confirmation" id="nuevo_password_confirmation" class="form-control" placeholder="Confirmar Contraseña" required>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('nuevo_password_confirmation', this)" title="Mostrar/Ocultar contraseña">👁️</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nuevo_idRol">Rol</label>
                        <select name="idRol" id="nuevo_idRol" class="form-select" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->idRol }}" {{ (old('idRol') == $rol->idRol || (old('idRol') === null && $rol->idRol == 1)) ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancelar" onclick="cerrarModalCrear()">Cancelar</button>
                    <button type="submit" class="btn-modal-guardar" style="background-color: #2563eb;">Agregar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PARA EDITAR USUARIO -->
    <div id="modalEditarUsuario" class="modal-overlay">
        <div class="modal-contenedor">
            <div class="modal-header">
                <h3>Editar Usuario</h3>
                <button type="button" class="modal-close" onclick="cerrarModalEditar()">&times;</button>
            </div>
            
            <form id="formEditarUsuario" action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <!-- Nombre y Apellido -->
                    <div class="form-row-dos">
                        <div class="form-group">
                            <label for="edit_nombre">Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_apellido">Apellido</label>
                            <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group">
                        <label for="edit_telefono">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control">
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group">
                        <label for="edit_email">Correo Electrónico</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>

                    <!-- Contraseña Opcional -->
                    <div class="form-group">
                        <label for="edit_password">Nueva Contraseña</label>
                        <div class="password-input-wrapper">
                            <input type="password" name="password" id="edit_password" class="form-control" >
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('edit_password', this)">👁️</button>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="form-group">
                        <label for="edit_idRol">Rol</label>
                        <select name="idRol" id="edit_idRol" class="form-select" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->idRol }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancelar" onclick="cerrarModalEditar()">Cancelar</button>
                    <button type="submit" class="btn-modal-guardar">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Abrir y Cerrar Modal de Crear Usuario
        function abrirModalCrear() {
            document.getElementById('modalNuevoUsuario').classList.add('activo');
        }

        function cerrarModalCrear() {
            document.getElementById('modalNuevoUsuario').classList.remove('activo');
        }

        document.getElementById('modalNuevoUsuario').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalCrear();
            }
        });

        // Función para mostrar / ocultar contraseña
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        // Abrir Modal de Edición
        function abrirModalEditar(id, nombre, apellido, telefono, email, idRol) {
            const form = document.getElementById('formEditarUsuario');
            form.action = "{{ url('/usuarios') }}/" + id;

            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_apellido').value = apellido;
            document.getElementById('edit_telefono').value = telefono || '';
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_idRol').value = idRol;
            document.getElementById('edit_password').value = '';

            document.getElementById('modalEditarUsuario').classList.add('activo');
        }

        // Cerrar Modal de Edición
        function cerrarModalEditar() {
            document.getElementById('modalEditarUsuario').classList.remove('activo');
        }

        // Cerrar modal al hacer clic en el fondo exterior
        document.getElementById('modalEditarUsuario').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalEditar();
            }
        });

        // Confirmar Eliminación nativo con JavaScript
        function confirmarEliminarUsuario(id, nombreCompleto) {
            if (confirm('¿Estás seguro de que deseas eliminar al usuario ' + nombreCompleto + '?')) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        }
    </script>
</body>
</html>
