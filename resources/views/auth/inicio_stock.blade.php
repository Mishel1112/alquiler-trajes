<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Stock - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}">
</head>
<body>

    <header class="topbar">
        <div class="logo-container">
            <span class="logo-text">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="user-info">
            <span class="user-icon">👤</span>
            <span class="user-role">Admin</span>
            <a href="{{ route('dashboard') }}" class="btn-salir">Dashboard</a>
        </div>
    </header>

    <div class="stock-header">
        <h1>Módulo de Stock</h1>
        <p>¿Qué operación desea realizar?</p>
    </div>

    <div class="categories-grid">
        <a href="{{ route('stock', ['modo' => 'alquilar']) }}" class="category-card-link">
            <div class="category-card tarjeta-opcion">
                <div class="icono-opcion"></div>
                <h3>📋REGISTRAR ALQUILER</h3>
                <p>Seleccionar un traje del inventario para enviarlo directamente al formulario de renta.</p>
            </div>
        </a>

        <a href="{{ route('stock', ['modo' => 'gestionar']) }}" class="category-card-link">
            <div class="category-card tarjeta-opcion">
                <div class="icono-opcion"></div>
                <h3>📦GESTIONAR STOCK</h3>
                <p>Ver catálogo completo, añadir nuevos trajes, editar o eliminar registros.</p>
            </div>
        </a>
    </div>

</body>
</html>