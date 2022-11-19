<?php

namespace Sgcomptech\FilamentTicketing\Models;

use Illuminate\Database\Eloquent\Model;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class Comment extends Model
{
	protected $guarded = [];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}

	public function user()
	{
		return $this->belongsTo(config('filament-ticketing.user-model'));
	}
}
