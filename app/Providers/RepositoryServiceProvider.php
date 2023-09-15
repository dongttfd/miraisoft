<?php

namespace App\Providers;

use App\Repositories\Contracts\AccountRepository;
use App\Repositories\Eloquent\AccountRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = $this->loadRepositoryBindings();

        if (!empty($bindings)) {
            foreach ($bindings as $abstract => $concrete) {
                $this->app->bind($abstract, $concrete);
            }
        }
    }

    /**
     * Config binding default of repository
     *
     * @return array
     */
    private function loadRepositoryBindings(): array
    {
        return [
            // alias here
            AccountRepository::class => AccountRepositoryEloquent::class,
        ];
    }
}
