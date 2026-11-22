# 🚀 Guía de Instalación y Puesta en Marcha

Esta guía detalla los pasos para instalar y ejecutar el **Sistema de Gestión y Control de Alquiler de Trajes** en un entorno local (WampServer, XAMPP o PHP nativo).

---

## 📋 Requisitos Previos

1. **Servidor Web Local:** WampServer (recomendado), XAMPP o Laragon.
2. **PHP:** Versión `8.1` o superior (con extensiones `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`).
3. **Gestor de Base de Datos:** MySQL `8.0+` o MariaDB `10.4+` (vía phpMyAdmin o terminal).
4. **Composer:** Gestor de paquetes de PHP (`v2.x`).

---

## 🛠️ Pasos de Instalación

### 1. Clonar o Colocar el Proyecto
Ubica la carpeta del proyecto en el directorio raíz de tu servidor web:
- **WampServer:** `C:\wamp64\www\alquiler-trajes` o `E:\wamp64\www\alquiler-trajes`
- **XAMPP:** `C:\xampp\htdocs\alquiler-trajes`

### 2. Configurar el Archivo de Entorno `.env`
Copia el archivo `.env.example` y nómbralo `.env`:
```bash
cp .env.example .env
```

Asegúrate de que la configuración de conexión a MySQL coincida con tu servidor:
```env
APP_NAME="Creaciones y Fantasías Erlinda"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost/alquiler-trajes/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bd_alquiler_trajes
DB_USERNAME=root
DB_PASSWORD=
```
*(Si tu usuario `root` de MySQL tiene contraseña, indícala en `DB_PASSWORD`)*.

### 3. Generar la Clave de Aplicación
Ejecuta en la terminal de la raíz del proyecto:
```bash
composer install
php artisan key:generate
```

### 4. Cargar la Base de Datos con Datos de Demostración
El proyecto incluye un script SQL maestro en `sql/script.sql`.

#### Opción A: Desde phpMyAdmin
1. Abre tu navegador en `http://localhost/phpmyadmin`.
2. Ve a la pestaña **Importar**.
3. Selecciona el archivo `sql/script.sql`.
4. Haz clic en **Importar** (el script crea automáticamente la base de datos `bd_alquiler_trajes` y carga toda la información).

#### Opción B: Desde la Consola MySQL
```bash
mysql -u root -p < sql/script.sql
```

### 5. Configurar Enlace Simbólico de Almacenamiento (Opcional)
Para garantizar el acceso a imágenes subidas:
```bash
php artisan storage:link
```

### 6. Ejecutar el Servidor
Puedes acceder mediante tu servidor local habitual:
```
http://localhost/alquiler-trajes/public/
```
O ejecutando el servidor integrado de Artisan:
```bash
php artisan serve
```
Y abriendo en tu navegador: `http://127.0.0.1:8000`.

---

## 🔐 Credenciales de Acceso Predeterminadas

El sistema viene preconfigurado con dos cuentas de usuario para pruebas y presentación:

| Rol | Correo Electrónico | Contraseña | Permisos |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@erlinda.com` | `12345678` | Control total: Catálogo, CRUD de trajes, reportes, usuarios y finanzas. |
| **Empleado** | `empleado@erlinda.com` | `12345678` | Operaciones: Mostrador, alquileres, reservas, devoluciones y stock. |

---

## ❓ Solución de Problemas Comunes

### Error: "Base de datos no seleccionada (#1046)"
El archivo `sql/script.sql` incluye las directivas:
```sql
CREATE DATABASE IF NOT EXISTS `bd_alquiler_trajes`;
USE `bd_alquiler_trajes`;
```
Si lo ejecutas manualmente, asegúrate de seleccionar la base de datos o ejecutar el script en la raíz del servidor SQL.

### Las vistas no reflejan cambios recientes en estilos CSS
Ejecuta la limpieza de caché de vistas de Laravel:
```bash
php artisan view:clear
php artisan cache:clear
```
Y en tu navegador presiona `Ctrl + F5` para forzar la recarga sin caché.
