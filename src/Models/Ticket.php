<?php

namespace SGCompTech\FilamentTicketing\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $fillable = ['ticketable_type', 'ticketable_id', 'name', 'email',
		'assigned_to', 'status', 'priority', 'title', 'content'];

	public function ticketable()
	{
		return $this->morphTo();
	}

	public function user()
	{
		$userModel = config('filament-ticketing.user-model');
		return $userModel ? $this->belongsTo(config('filament-ticketing.user-model')) : null;
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function assigned_to()
	{
		return $this->belongsTo(config('filament-ticketing.user-model'));
	}
}
