<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    
    public function boot(): void
    {
        //Usuarios que pueden acceder al panel
        Gate::define("acceder-panel", function(User $user){
            return in_array($user->rol, ["administrador", "operador"], true);
        });

        //Permisos administrador
        Gate::define("crear-usuarios", function(User $user){
            return $user->rol === "administrador";
        });

        Gate::define("eliminar-registros", function(User $user){
            return $user->rol === "administrador";
        });

        Gate::define("gestionar-responsables", function(User $user){
            return $user->rol === "administrador";
        });


    }
}
