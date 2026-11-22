<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\UsuarioController;

Route::get('/dashboard', [IncidenteController::class, 'dashboard'])->name('incidentes.dashboard');
Route::get('/incidentes/dashboard/datos', [IncidenteController::class, 'dashboardDatos'])->name('incidentes.dashboardDatos');
Route::view("/mapa", "incidentes.mapa")->name("incidentes.mapa");

//Acceso Invitados
Route::middleware("guest")->group(function() {
    Route::get("/login", [AuthController::class, "create"])->name("login");
    Route::post("/login", [AuthController::class, "store"])->name("login.store");
});


//Panel: ambos roles

Route::middleware(["auth", "can:acceder-panel"])->group(function() {
    
    //Cerrar session
    Route::post("/logout", [AuthController::class, "destroy"])->name("logout");

    //dashboard/Inicio
    Route::get("/", [IncidenteController::class, "dashboard"])->name("incidentes.dashboard");
    Route::get("/dashboard/datos", [IncidenteController::class, "dashboardDatos"])->name("incidentes.dashboardDatos");


    // rutas Incidentes
    Route::view("/mapa", "incidentes.mapa")->name("incidentes.mapa");
    Route::get("/incidentes", [IncidenteController::class, "index"])->name("incidentes.index");
    Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
    Route::get("/incidentes/todas", [IncidenteController::class, "obtenerDatos"])->name("incidentes.obtenerDatos");
    Route::post("/incidentes", [IncidenteController::class, "store"])->name("incidentes.store");
    Route::get("/incidentes/{id}", [IncidenteController::class, "show"])->name("incidentes.show");
    Route::get("/incidentes/{id}/edit", [IncidenteController::class, "edit"])->name("incidentes.edit");
    Route::put("/incidentes/{id}", [IncidenteController::class, "update"])->name("incidentes.update");

    // rutas Choferes
    Route::get('/choferes/crear', [ChoferController::class, 'create'])->name('choferes.create');  
    Route::get("choferes/datos", [ChoferController::class, "obtenerDatos"])->name("choferes.obtenerDatos");
    Route::post('/choferes', [ChoferController::class, 'store'])->name('choferes.store');
    Route::get('/choferes/{id}/editar', [ChoferController::class, 'edit'])->name('choferes.edit');
    Route::put('/choferes/{id}', [ChoferController::class, 'update'])->name('choferes.update');
    Route::get('/choferes/{id}', [ChoferController::class, 'show'])->name('choferes.show');

    // rutas Vehiculo
    Route::get('/vehiculos/crear', [VehiculoController::class, 'create'])->name('vehiculos.create');  
    Route::get("/vehiculos/datos", [VehiculoController::class, "obtenerDatos"])->name("vehiculos.obtenerDatos");
    Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
    Route::get('/vehiculos/{id}/editar', [VehiculoController::class, 'edit'])->name('vehiculos.edit');
    Route::put('/vehiculos/{id}', [VehiculoController::class, 'update'])->name('vehiculos.update');
    Route::get('/vehiculos/{id}', [VehiculoController::class, 'show'])->name('vehiculos.show');


    // RUTAS ADMINISTRADOR
    Route::middleware("can:crear-usuarios")->group(function() {
        //Crear usuarios
        Route::get("/usuarios/crear", [UsuarioController::class, "create"])->name("usuarios.create");
        Route::post("/usuarios", [UsuarioController::class, "store"])->name("usuarios.store");
        
    });

    Route::middleware("can:eliminar-registros")->group(function() {
        //Eliminar incidentes/choferes/vehiculos
        Route::delete("/incidentes/{id}", [IncidenteController::class, "destroy"])->name("incidentes.destroy");
        Route::delete("/choferes/{id}", [ChoferController::class, "destroy"])->name("choferes.destroy");
        Route::delete("/vehiculos/{id}", [VehiculoController::class, "destroy"])->name("vehiculos.destroy");
    });

    Route::middleware("can:gestionar-responsables")->group(function() {
        //Asignar y desligar usuario de incidente (responsable de investigacion)
        Route::post("/incidentes/{id}/responsables/", [IncidenteController::class, "asignarResponsable"])->name("incidentes.responsables.store");
        Route::delete("/incidentes/{id}/responsables/{usuario}", [IncidenteController::class, "quitarResponsable"])->name("incidentes.responsables.destroy");
    });

});

