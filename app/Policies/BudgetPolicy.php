<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BudgetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Budget $budget): bool
    {
        return $user->id === $budget->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Budget $budget): bool
    {
        return $user->id === $budget->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Budget $budget): bool
    {
        return $user->id === $budget->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Budget $budget): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Budget $budget): bool
    {
        return false;
    }
}
