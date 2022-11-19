<?php

namespace Sgcomptech\FilamentTicketing\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $guarded = [];

	public function ticketable()
	{
		return $this->morphTo();
	}

	public function user()
	{
		return $this->belongsTo(config('filament-ticketing.user-model'));
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function assigned_to()
	{
		return $this->belongsTo(config('filament-ticketing.user-model'));
	}

	public function priorityColor()
	{
		$colors = ['primary', 'success', 'warning', 'danger'];
		return $colors[$this->priority] ?? 'danger';
	}
}
