<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Company;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('viewApiDocs', function ($user = null) {
            if (app()->environment('local')) return true;
            return optional($user)->email === config('app.admin_email');
        });

        Gate::define('company.manage', function ($user, Company $company) {
            // allow global admin by configured admin email
            if (optional($user)->email === config('app.admin_email')) return true;

            // allow super-admin role if Spatie or similar is installed and user has role
            try {
                if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ignore if role check fails
            }

            // allow boolean attribute is_super_admin if present on user model
            if (isset($user->is_super_admin) && $user->is_super_admin) return true;

            // company owner or pivot role 'admin'
            if ($company->owner_user_id === optional($user)->id) return true;
            $membership = $company->users()->where('user_id', optional($user)->id)->first();
            return $membership && $membership->pivot->role === 'admin';
        });
    }
}
