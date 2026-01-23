<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Fortify;

class JetstreamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configurePermissions();
        Jetstream::deleteUsersUsing(DeleteUser::class);

        // Redirection après login
        Fortify::redirects('login', function () {
            $user = auth()->user();

            if ($user && ($user->isAdmin() || $user->isFormateur())) {
                return route('admin.dashboard');
            }

            return route('front.students.index');
        });
    }

    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);
        Jetstream::permissions(['create', 'read', 'update', 'delete']);
    }
}
