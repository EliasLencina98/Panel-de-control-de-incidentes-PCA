# Panel de control de incidentes

Aplicación web desarrollada en **Laravel** para el registro, seguimiento, análisis georreferenciado y gestión de incidentes viales/operativos, junto con el control de choferes, vehículos y responsables de investigación.

---

## Descripción general

**Panel de control de incidentes** centraliza la información operativa de incidentes y flota en un panel web. Permite registrar eventos con datos temporales, condiciones ambientales y de calzada, ubicación geográfica (latitud/longitud y referencia), chofer y vehículo involucrados, además de calcular automáticamente la **gravedad** del incidente a partir de una matriz de severidad según el tipo de evento, cantidad de heridos y fatalidades.

---

## Funcionalidades implementadas

### 1. Autenticación, roles y permisos
* **Inicio y cierre de sesión** (`AuthController`) con normalización de correo electrónico, regeneración de sesión y protección CSRF.
* **Control de acceso basado en roles y Gates** (`AppServiceProvider`):
  * **Administrador** y **Operador**: acceso al panel principal, gestión de incidentes, choferes y vehículos, visualización de mapas/dashboard y consulta de su perfil.
  * **Administrador**: permisos exclusivos para registrar nuevos usuarios (`crear-usuarios`), eliminar registros (`eliminar-registros`) y asignar o remover responsables de investigación (`gestionar-responsables`).
* **Perfil de usuario** (`PerfilController`): muestra los datos del usuario autenticado y el historial de incidentes en los que figura como responsable de investigación, junto con la fecha de asignación.

### 2. Gestión de incidentes y matriz de gravedad
* **Cálculo automático de gravedad** (`Incidente::calcularGravedad`):
  * Si hay **fatalidades >= 1**, la gravedad se clasifica automáticamente como **`fatal`**.
  * En caso contrario, evalúa una **matriz bidimensional** según el `tipo` de incidente y la cantidad de `heridos` (`0`, `1` o `2+`) para determinar si es `leve`, `moderado` o `grave` (recalculándose también al editar el registro).
* **Tipos de incidentes contemplados**: colisión/choque vehicular, impacto objeto fijo, vuelco, despiste, atropello, falla mecánica, principio de incendio/incendio, daño menor y casi incidente.
* **Seguimiento e investigación**: gestión de estado (`pendiente`, `en proceso`, `resuelto`), `causa_raiz`, `accion_correlativa` y asignación/desvinculación de usuarios responsables de investigación desde el detalle del incidente.
* **Búsqueda y filtrado** (`obtenerDatos`): consulta dinámica por rango de fechas, gravedad, tipo, estado o texto libre (tipo, nombre de chofer o patente de vehículo).
* **Protección al eliminar**: impide borrar choferes o vehículos que tengan incidentes asociados.

### 3. Gestión de flota (Choferes y Vehículos)
* **Choferes** (`ChoferController`): alta, edición, consulta de ficha y búsqueda por nombre, DNI o licencia; valida edad mínima de 18 años y fecha de ingreso no futura.
* **Vehículos** (`VehiculoController`): alta, edición, consulta de ficha y búsqueda por patente, modelo o tipo (`transporte de personal`, `pesado`, `liviano`), con normalización automática de patente en mayúsculas.

### 4. Dashboard analítico y mapas interactivos
* **Dashboard** (`dashboard` / `dashboardDatos`):
  * **KPIs y porcentajes** por gravedad (`total`, `leves`, `moderados`, `graves`, `fatales`).
  * **Gráficos estadísticos** con **ApexCharts** (evolución temporal por mes, clasificación por gravedad y distribución por tipo).
  * **Minimapa con mapa de calor** mediante **Leaflet** y `leaflet.heat`.
  * **Filtros combinables** por fecha desde/hasta, gravedad, tipo y estado.
* **Vista de mapa completa** (`mapa.blade.php` + `mapa-vistas.js`): exploración geográfica con filtros y alternancia entre marcadores y mapa de calor.
* **Selección en mapa al cargar incidentes** (`create.blade.php` + `mapa-registro.js`): selección interactiva de coordenadas (`latitud` y `longitud`) sobre el mapa.

---

## Stack tecnológico

* **Backend:** PHP `^8.3`, Laravel `^13.17`, Eloquent ORM
* **Base de datos:** SQLite (`database/database.sqlite`, configurable vía `.env`)
* **Frontend:** Blade Templates, CSS personalizado, JavaScript (`public/js/*`)
* **Visualización de datos y mapas:** ApexCharts, Leaflet.js, Leaflet.heat

---

## Estructura principal del proyecto

```text
├── app/
│   ├── Http/Controllers/     # AuthController, IncidenteController, ChoferController, VehiculoController, UsuarioController, PerfilController
│   ├── Models/               # Incidente, Vehiculo, Chofer, User
│   └── Providers/            # AppServiceProvider (definición de Gates y permisos)
├── database/
│   ├── migrations/           # Esquema de tablas y modificaciones incrementales
│   └── seeders/              # Datos iniciales de prueba (vehículos, choferes, usuarios, incidentes, responsables)
├── public/
│   ├── css/                  # Estilos del panel, dashboard, gestión, mapas, incidentes y perfil
│   └── js/                   # Scripts de dashboard, mapas, listados y pestañas de gestión
├── resources/views/
│   ├── layouts/              # Plantilla base (panel, header, sidebar)
│   └── incidentes/           # Vistas de dashboard, mapa, incidentes, choferes, vehículos, usuarios, perfil y login
└── routes/web.php            # Definición de rutas públicas, autenticadas y por rol
```

---

## Instalación y puesta en marcha

1. **Clonar el repositorio e ingresar al directorio:**
   ```bash
   git clone https://github.com/EliasLencina98/Panel-de-control-de-incidentes-PCA
   cd Panel-de-control-de-incidentes-PCA
   ```

2. **Instalar dependencias de PHP y Node:**
   ```bash
   composer install
   ```

3. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Por defecto está configurado para usar SQLite; asegurate de contar con el archivo `database/database.sqlite` o ajustar los parámetros de base de datos en `.env`).*

4. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Compilar assets y levantar el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```

---

## Usuarios de prueba (Seeders)

Al ejecutar `UsuarioSeeder` se cargan usuarios de ejemplo (contraseña para todos: `12345678`):

| Rol | Nombre | Correo electrónico | DNI |
| :--- | :--- | :--- | :--- |
| `operador` | Ana Sosa | `ana.sosa@prueba.com` | `27333444` |
| `administrador` | Diego Perez | `diego.perez@prueba.com` | `30555666` |
| `administrador` | Admin prueba | `admin@prueba.com` | `12345678` |

---

## Autores

- [**Elias Lencina**](https://github.com/EliasLencina98)
- [**Leonardo Carrasco**](https://github.com/LeonardoCarrasco)