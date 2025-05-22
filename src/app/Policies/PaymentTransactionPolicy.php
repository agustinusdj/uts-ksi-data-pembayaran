<?php

namespace App\Policies;

use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentTransactionPolicy
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
    public function view(User $user, PaymentTransaction $paymentTransaction): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow both authenticated users and admins to create transactions
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentTransaction $paymentTransaction): bool
    {
        // Only super_admin can update transaction records
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentTransaction $paymentTransaction): bool
    {
        // Only super_admin can delete transaction records
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentTransaction $paymentTransaction): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentTransaction $paymentTransaction): bool
    {
        return $user->hasRole('super_admin');
    }
}
