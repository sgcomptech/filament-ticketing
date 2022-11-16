<?php

namespace Sgcomptech\FilamentTicketing\Interfaces;

interface HasTickets
{
	/**
	 * Get the name of the model class that is impemented on.
	 *
	 * @return string
	 */
	public function model_class(): string;

	/**
	 * Get the name of the model name column. Default to 'name'.
	 *
	 * @return string
	 */
	public function model_name(): string;

	/**
	 * Get the collection of tickets
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function tickets();

	/**
	 * Get the latest ticket
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function latestTicket();
}
