<?php

namespace Sgcomptech\FilamentTicketing\Interfaces;

use Illuminate\Foundation\Auth\User;

interface TicketPolicies
{
	/**
	 * Determine whether the user can manage all tickets.
	 *
	 * @param  \Illuminate\Foundation\Auth\User  $user
	 * @return bool
	 */
	public function manageAllTickets(User $user): bool;

	/**
	 * Determine whether the user can manage only tickets that are assigned to him.
	 *
	 * @param  \Illuminate\Foundation\Auth\User  $user
	 * @return bool
	 */
	public function manageAssignedTickets(User $user): bool;

	/**
	 * Determine whether the user can assign tickets to other user.
	 *
	 * @param  \Illuminate\Foundation\Auth\User  $user
	 * @return bool
	 */
	public function assignTickets(User $user): bool;
}
