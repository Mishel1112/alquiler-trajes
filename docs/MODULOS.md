# 📦 Módulos del Sistema — Creaciones y Fantasías Erlinda

Este documento detalla el funcionamiento, flujo operativo y reglas de negocio implementadas en cada módulo del sistema.

---

## 📑 Tabla de Contenidos
1. [Panel de Control (Dashboard)](#1-panel-de-control-dashboard)
2. [Catálogo y Gestión de Stock](#2-catálogo-y-gestión-de-stock)
3. [Módulo de Alquiler Inmediato](#3-módulo-de-alquiler-inmediato)
4. [Módulo de Reservas](#4-módulo-de-reservas)
5. [Control de Alquileres y Devoluciones](#5-control-de-alquileres-y-devoluciones)
6. [Módulo de Reportes Analíticos](#6-módulo-de-reportes-analíticos)
7. [Gestión de Usuarios y Seguridad](#7-gestión-de-usuarios-y-seguridad)

---

## 1. Panel de Control (Dashboard)
El centro de mando de la tienda ofrece un resumen en tiempo real del estado del negocio al iniciar sesión.

- **Métricas del Día:**
  - **Trajes Salidos Hoy:** Suma total de unidades de prendas entregadas en la fecha actual (`sum('cantidad')`).
  - **Recaudación Hoy:** Ingresos totales generados en el día en bolivianos (Bs.).
  - **Alquileres Activos:** Total de alquileres vigentes pendientes de devolución.
  - **Reservas Pendientes:** Contratos reservados pendientes de entrega futura.
- **Gráficas de Rendimiento:**
  - Tendencia de recaudación de los últimos 7 días.
  - Distribución de demanda por categorías.
- **Accesos Rápidos:** Enlaces directos a registrar alquiler, revisar inventario y generar reportes.

---

## 2. Catálogo y Gestión de Stock
Control centralizado de trajes e inventario clasificado por categorías folclóricas y temáticas:

### Categorías Soportadas:
1. **Trajes Típicos:** Danzas tradicionales bolivianas (Caporal, Morenada, Cueca Tarijeña, Cueca Chapaca, Tinku, Tobas, Diablada, Kullawada, etc.).
2. **Trajes Internacionales:** Vestimentas tradicionales de México, Colombia, España, Perú, etc.
3. **Disfraces:** Trajes infantiles, superhéroes, personajes de temporada (Halloween, Navidad, fiestas escolares).
4. **Trajes de Gala:** Vestidos de noche, trajes formales y ternos de etiqueta.

### Características del Módulo:
- **Gestión por Tallas:** Cada traje soporta desglose individual de stock por tallas (`XS`, `S`, `M`, `L`, `XL`).
- **Control de Piezas:** Especificación detallada de los accesorios que conforman cada traje (ej: *sombrero, faja, chaleco, pantalón, pollerín*) y conteo automático de componentes.
- **Disponibilidad Dinámica:** Cálculo automático de disponibilidad (`Disponible` o `Agotado`) según el stock consolidado de tallas.
- **Mantenimiento (CRUD):** Solo accesible para usuarios con rol `Administrador`, permitiendo añadir trajes, editar especificaciones, actualizar imágenes y eliminar prendas con validación de dependencias.

---

## 3. Módulo de Alquiler Inmediato
Proceso ágil para alquilar uno o múltiples trajes en el mostrador:

1. **Selección desde Catálogo:**
   - Navegación visual por categorías con imágenes completas sin recorte.
   - Carrito flotante interactivo que acumula prendas, tallas y cantidades en tiempo real.
2. **Formulario de Registro:**
   - Selección o creación rápida de cliente (Nombre, CI, Teléfono, Tipo de Cliente: Colegio, Fraternidad, Particular).
   - Fechas operativas: Fecha de recogida y fecha pactada de devolución.
   - **Registro de Garantía:** Monto económico en custodia o descripción de documento/garantía física (Cédula de identidad, licencia, etc.).
3. **Confirmación y Recibo:**
   - Descuento automático de stock de las tallas seleccionadas.
   - Generación de comprobante oficial de alquiler con número de recibo correlativo y versión lista para imprimir.

---

## 4. Módulo de Reservas
Diseñado para eventos escolares, entradas folclóricas y festividades con anticipación:

- **Seña / Anticipo:** Registro del monto de reserva pagado por el cliente para apartar las prendas.
- **Reserva de Stock:** Las prendas quedan comprometidas para la fecha programada.
- **Entrega de Reserva:** Cuando el cliente se presenta a recoger el pedido, el sistema permite saldar el saldo pendiente, registrar la garantía final y cambiar el estado a `Activo` con entrega de prendas.

---

## 5. Control de Alquileres y Devoluciones
Pestaña integral organizada en cuatro secciones:

- **1. Alquileres Activos:** Lista de contratos vigentes en poder del cliente. Incluye indicador de fecha de devolución programada y alertas de retraso.
- **2. Devoluciones / Lavandería:**
  - Registro de recepción de prendas.
  - Evaluación del estado de la ropa: Opción de registrar cobro adicional por roturas, manchas o prendas dañadas.
  - Derivación automática al estado `En Lavado` para higiene y reacondicionamiento.
- **3. Reingreso al Stock:**
  - Botón de confirmación para retornar las prendas lavadas al inventario disponible, incrementando nuevamente el stock por talla.
- **4. Historial Completo:**
  - Visualización paginada de todos los alquileres finalizados con información de cliente, fecha de reingreso, usuario cajero responsable y acceso al recibo original.

---

## 6. Módulo de Reportes Analíticos
Información gerencial protegida para toma de decisiones:

- **Reporte Diario:**
  - Filtro por fecha específica (por defecto el día en curso).
  - Total de ingresos recaudados y conteo de prendas entregadas.
  - Tabla detallada de transacciones del día con cliente, trajes y hora de salida.
- **Reporte Mensual:**
  - Filtro por mes y año.
  - Distribución de recaudación agrupada por semanas.
  - Métricas de demanda y flujo de inventario del mes.
- **Reporte Anual Multianual:**
  - Selector dinámico de gestión (por defecto muestra la gestión actual `2026`).
  - Capacidad de consultar gestiones históricas anteriores (`2025`, `2024`, etc.).
  - Balance de recaudación, trajes entregados y contratos celebrados.
  - Indicador de crecimiento porcentual respecto a la gestión anterior.
  - Desglose mensual de los 12 meses con barras de aporte porcentual y detección del mes pico de demanda.
  - Resumen acumulado global de la historia del negocio.

---

## 7. Gestión de Usuarios y Seguridad
- **Control de Roles:**
  - `Administrador`: Acceso completo a inventario, creación de trajes, módulos de reportes financieros y gestión de cuentas.
  - `Empleado / Cajero`: Acceso a operaciones de mostrador (alquileres, reservas, devoluciones, recibos y consulta de stock).
- **Seguridad:**
  - Autenticación mediante `Hash::make()` (Bcrypt).
  - Protección de rutas con middleware `auth` y `admin`.
  - Protección CSRF en todos los formularios.
