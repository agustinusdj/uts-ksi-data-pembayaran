<?php

namespace App\Providers;

use App\Models\PaymentAnalytic;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Policies\PaymentAnalyticPolicy;
use App\Policies\PaymentGatewayPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\PaymentTransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PaymentGateway::class => PaymentGatewayPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        PaymentTransaction::class => PaymentTransactionPolicy::class,
        PaymentAnalytic::class => PaymentAnalyticPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define role-based permissions
        Gate::define('access-admin', function ($user) {
            return $user->hasAnyRole(['admin', 'super_admin']);
        });

        Gate::define('access-super-admin', function ($user) {
            return $user->hasRole('super_admin');
        });
    }
}
