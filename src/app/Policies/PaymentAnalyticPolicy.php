<?php

namespace App\Policies;

use App\Models\PaymentAnalytic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentAnalyticPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentAnalytic $paymentAnalytic): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super_admin can manually create analytics
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentAnalytic $paymentAnalytic): bool
    {
        // Only super_admin can update analytics
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentAnalytic $paymentAnalytic): bool
    {
        // Only super_admin can delete analytics
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentAnalytic $paymentAnalytic): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentAnalytic $paymentAnalytic): bool
    {
        return $user->hasRole('super_admin');
    }
}
