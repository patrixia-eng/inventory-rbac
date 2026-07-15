<?php 
  
namespace App\Providers; 
  
use App\Models\Permission; 
use App\Models\User; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\ServiceProvider; 
  
class AppServiceProvider extends ServiceProvider 
{ 
    public function register(): void 
    { 
        // 
    } 
  
    public function boot(): void 
    { 
        /* 
     | Super-admin bypass: runs BEFORE every Gate and Policy check. 
         | Returning true grants; returning null falls through to the 
         | normal Gate/Policy logic. 
         */ 
        Gate::before(function (User $user, string $ability) { 
            if ($user->hasRole('system-administrator')) { 
                return true; 
            } 
  
            return null; 
        }); 
  
        /* 
         | Dynamic Gates: one Gate per permission row in the database 
         | (can_create, can_update, can_view, can_remove, can_print). 
         | The try/catch + hasTable guard keeps artisan commands working 
         | before the RBAC migrations have run. 
         */ 
        try { 
            if (Schema::hasTable('permissions')) { 
                Permission::pluck('slug')->each(function (string $slug) { 
                    Gate::define($slug, fn (User $user) => $user->hasPermission($slug)); 
                }); 
            } 
        } catch (\Throwable) { 
            // Database not ready (e.g. during composer install / CI). 
        } 
    } 
} 