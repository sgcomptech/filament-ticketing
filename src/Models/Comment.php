<?php

namespace Sgcomptech\FilamentTicketing\Models;

use Illuminate\Database\Eloquent\Model;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class Comment extends Model
{
	protected $fillable = ['name', 'email',
		'assigned_to', 'status', 'priority', 'title', 'content'];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}

	public function user()
	{
		$userModel = config('filament-ticketing.user-model');
		return $userModel ? $this->belongsTo(config('filament-ticketing.user-model')) : null;
	}
}
