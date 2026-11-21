<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="reserva.css">
</head>
<body>

    <!-- Encabezado del Sistema -->
    <header class="admin-header">
        <div class="logo">Creaciones y Fantasías Erlinda</div>
        <div class="user-info">
            <span>👤 Administrador</span>
            <a href="logout.php" class="btn-salir">Salir</a>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <main class="container">
        
        <div class="page-title">
            <h2>Gestión de Reservas y Alquileres</h2>
            <button class="btn-primary" onclick="alert('Aquí se abriría el formulario para nueva reserva')">+ Nueva Reserva</button>
        </div>

        <!-- Filtros y Buscador -->
        <div class="filters-bar">
            <input type="text" placeholder="Buscar por cliente o traje..." class="search-input">
            <select class="filter-select">
                <option value="">Todos los Estados</option>
                <option value="Reservado">Reservado</option>
                <option value="Entregado">Entregado</option>
                <option value="Devuelto">Devuelto</option>
            </select>
        </div>

        <!-- Tabla de Reservas -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Traje / Categoría</th>
                        <th>Fecha Préstamo</th>
                        <th>Estado de Reserva</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#101</td>
                        <td>María Gómez</td>
                        <td>Chapaquita (Trajes Típicos)</td>
                        <td>12/09/2026</td>
                        <td><span class="badge reservado">Reservado</span></td>
                        <td>
                            <button class="btn-action">Editar</button>
                            <button class="btn-action delete">Cancelar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#102</td>
                        <td>Carlos Pérez</td>
                        <td>Macheteros (Trajes Típicos)</td>
                        <td>10/09/2026</td>
                        <td><span class="badge entregado">Entregado</span></td>
                        <td>
                            <button class="btn-action">Editar</button>
                            <button class="btn-action">Devolver</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#103</td>
                        <td>Ana Suárez</td>
                        <td>Vestido de Gala Rojo (Gala)</td>
                        <td>05/09/2026</td>
                        <td><span class="badge devuelto">Devuelto</span></td>
                        <td>
                            <button class="btn-action">Ver Detalle</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>