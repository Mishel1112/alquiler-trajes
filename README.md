# 🎭 Creaciones y Fantasías Erlinda
### Sistema Integral de Gestión y Control de Alquiler de Trajes Folclóricos y de Eventos

![Estado](https://img.shields.io/badge/Estado-Producci%C3%B3n-success?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10%20%7C%2011-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?style=for-the-badge&logo=css3&logoColor=white)

---

## 📖 Descripción General

**Creaciones y Fantasías Erlinda** es una plataforma web desarrollada a medida para modernizar, agilizar y controlar las operaciones comerciales de una tienda especializada en el **alquiler, reserva y confección de trajes típicos folclóricos, vestimentas internacionales, disfraces infantiles y trajes de gala**.

El sistema resuelve los desafíos operativos críticos del negocio:
- Control de inventario desagregado por tallas (`XS`, `S`, `M`, `L`, `XL`) y piezas físicas por prenda.
- Flujo de reservas con anticipos (señas) y garantías en custodia.
- Gestión de entregas, seguimiento de alquileres activos y alertas de vencimiento.
- Recepción de prendas con inspección física, cobros por deterioro/roturas y control de lavandería antes del reingreso al stock.
- Generación de recibos formales e imprimibles con correlativo numérico.
- Inteligencia comercial con reportes diarios, mensuales y multianuales comparativos.

---

## 📚 Documentación Detallada

Para consultar la documentación técnica y funcional en profundidad, revisa los siguientes manuales dedicados:

- 📦 [**Manual de Módulos y Flujos Operativos**](docs/MODULOS.md): Guía paso a paso de cada funcionalidad (Dashboard, Catálogo, Alquileres, Reservas, Devoluciones, Lavandería, Reportes y Usuarios).
- 🗄️ [**Modelo de Base de Datos y Diccionario**](docs/BASE_DE_DATOS.md): Diagrama Entidad-Relación, descripción de las 10 tablas relacionales y reglas de integridad referencial.
- 🚀 [**Manual de Instalación y Despliegue**](docs/INSTALACION.md): Guía para configurar WAMP/XAMPP, importar la base de datos `bd_alquiler_trajes` y resolver incidencias comunes.

---

## ✨ Características Principales

### 1. 📊 Panel de Control Inteligente (Dashboard)
- Resumen en tiempo real de trajes salidos hoy (sumatoria de prendas físicas entregadas).
- Métricas de recaudación del día, contratos activos y reservas pendientes.
- Gráfica de evolución semanal de ingresos y distribución de demanda por categorías.

### 2. 👗 Catálogo Visual y Control de Stock
- **4 Categorías Principales:** *Trajes Típicos*, *Trajes Internacionales*, *Disfraces* y *Trajes de Gala*.
- Visualización de imágenes completas y nítidas sin recortes con galería adaptativa.
- Desglose por tallas con conteo automático de disponibilidad (`Disponible` / `Agotado`).
- Control de las piezas que componen cada atuendo (sombreros, fajas, blusas, chalecos, polleras).
- Módulo de administración para añadir, editar o dar de baja trajes con imágenes previas.

### 3. 📋 Alquiler Inmediato y Carrito de Selección
- Carrito flotante en vivo para seleccionar múltiples prendas y tallas simultáneamente.
- Registro o búsqueda rápida de clientes (particulares, colegios, cursos, fraternidades).
- Gestión de garantías en custodia (dinero en efectivo o descripción de documentos).
- Generación automática de recibo formal en formato comprobante e impresión.

### 4. 📅 Reservas Anticipadas
- Bloqueo anticipado de stock para festividades folclóricas y eventos escolares.
- Registro de seña/anticipo económico.
- Módulo de entrega final donde se liquida el saldo restante y se emite la prenda.

### 5. 🔄 Ciclo de Devolución, Lavandería y Reingreso
- Recepción de prendas devueltas por el cliente con verificación de estado.
- Registro de cobros adicionales por daños, manchas o roturas.
- Tránsito por **Lavandería** (`En Lavado`) para limpieza y preparación.
- Botón de **Reingreso al Stock** que suma nuevamente las unidades disponibles a sus tallas correspondientes.
- **Historial Paginado** de todos los alquileres finalizados con búsqueda y acceso a recibos.

### 6. 📈 Reportes Financieros y Estadísticos
- **Reporte Diario:** Transacciones, prendas y recaudación detallada del día con selector de fecha.
- **Reporte Mensual:** Ingresos agrupados por semanas, flujo de prendas y comparativas.
- **Reporte Anual Multianual:**
  - Balance de la gestión actual en tiempo real.
  - Selector dinámico de gestiones anteriores (`2026`, `2025`, `2024`...) para consultar el historial cerrado.
  - Comparativa porcentual de crecimiento respecto al año anterior.
  - Tabla de desglose de los 12 meses con barras de progreso y detección del mes pico de demanda.
  - Resumen acumulado global histórico.

### 7. 👥 Seguridad y Roles de Usuario
- **Administrador:** Acceso irrestricto a finanzas, reportes, creación de trajes y gestión de personal.
- **Empleado / Cajero:** Acceso enfocado a mostrador, alquileres, reservas, devoluciones y stock.
- Contraseñas cifradas mediante algoritmo `Bcrypt`.

---

## 🛠️ Tecnologías Empleadas

| Capa | Tecnología |
| :--- | :--- |
| **Backend** | PHP 8.1+, Laravel Framework (Eloquent ORM, Blade, Routing, Middlewares) |
| **Base de Datos** | MySQL 8.0 / MariaDB con motor InnoDB y claves foráneas en cascada |
| **Frontend** | HTML5 semántico, CSS3 Vanilla con diseño responsivo y tipografía Inter |
| **Librerías UI** | SweetAlert2 (alertas interactivas), Chart.js (gráficas analíticas) |
| **Entorno Local** | WampServer 64-bit / Apache 2.4 |

---

## 🚀 Inicio Rápido

1. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```

2. **Cargar la Base de Datos:**
   Importa el archivo [`sql/script.sql`](sql/script.sql) en phpMyAdmin o MySQL. Este script crea automáticamente la base de datos `bd_alquiler_trajes` y carga 23 trajes reales con imágenes, 57 tallas/stocks, 15 clientes y 35 contratos de alquiler para demostración.

3. **Iniciar el servidor:**
   ```bash
   php artisan serve
   ```
   Abre en tu navegador: `http://127.0.0.1:8000`

---

## 🔑 Cuentas de Acceso para Pruebas

| Rol | Correo | Contraseña |
| :--- | :--- | :--- |
| 👑 **Administrador** | `admin@erlinda.com` | `12345678` |
| 👤 **Empleado** | `empleado@erlinda.com` | `12345678` |

---

## 📁 Estructura del Proyecto

```text
alquiler-trajes/
├── app/
│   ├── Http/Controllers/
│   │   ├── AlquilerController.php     # Gestión de alquileres, reservas, devoluciones e historial
│   │   ├── AuthController.php         # Control de inicio y cierre de sesión
│   │   ├── DashboardController.php    # Métricas y gráficas del panel de control
│   │   ├── ReporteController.php      # Reportes diario, mensual y anual multianual
│   │   ├── TrajeController.php        # Catálogo, tallas, piezas y CRUD de prendas
│   │   └── UsuarioController.php      # Administración de usuarios y roles
│   └── Models/                        # Modelos Eloquent (Traje, TallaTraje, Usuario, etc.)
├── docs/                              # Documentación técnica y manuales
│   ├── BASE_DE_DATOS.md               # Diagrama relacional y diccionario de datos
│   ├── INSTALACION.md                 # Guía de instalación y solución de problemas
│   └── MODULOS.md                     # Guía funcional de los módulos del sistema
├── public/
│   ├── css/                           # Hojas de estilo modulares (theme.css, stock.css, etc.)
│   ├── img/                           # Fotografías reales del catálogo de trajes
│   └── images/                        # Iconografía e imágenes de interfaz
├── resources/views/
│   ├── auth/                          # Vistas del sistema (alquiler, stock, reportes, usuarios)
│   └── dashboard.blade.php            # Vista del panel de inicio
├── routes/
│   └── web.php                        # Definición de rutas y middlewares de acceso
├── sql/
│   └── script.sql                     # Script maestro DDL y datos de presentación
└── README.md                          # Documento principal del proyecto
```

---

## 📄 Licencia y Créditos

Proyecto desarrollado para **Creaciones y Fantasías Erlinda**.  
Todos los derechos reservados © 2026.
