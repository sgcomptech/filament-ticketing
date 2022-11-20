<?php

namespace Sgcomptech\FilamentTicketing\Tests\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Sgcomptech\FilamentTicketing\Tests\User;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Sgcomptech\FilamentTicketing\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ticket $ticket)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Sgcomptech\FilamentTicketing\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ticket $ticket)
    {
        return true;
        /* return ($user->id == 1) || str_contains($user->name, 'Manager')
            || str_contains($user->name, 'Administrator')
            || str_contains($user->name, 'Support');*/
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Sgcomptech\FilamentTicketing\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ticket $ticket)
    {
        return ($user->id == 1) || str_contains($user->name, 'Administrator');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Sgcomptech\FilamentTicketing\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Ticket $ticket)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Sgcomptech\FilamentTicketing\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Ticket $ticket)
    {
        return false;
    }

    public function deleteTickets(User $user)
    {
        return ($user->id == 1) || str_contains($user->name, 'Administrator');
    }

    public function manageAllTickets(User $user)
    {
        return ($user->id == 1) || str_contains($user->name, 'Administrator')
          || str_contains($user->name, 'Manager');
    }

    public function manageAssignedTickets(User $user)
    {
        return ($user->id == 1) || str_contains($user->name, 'Administrator')
          || str_contains($user->name, 'Manager')
          || str_contains($user->name, 'Support');
    }

    public function assignTickets(User $user)
    {
        return ($user->id == 1) || str_contains($user->name, 'Administrator')
          || str_contains($user->name, 'Manager');
    }
}