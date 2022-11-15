<?php

namespace SGCompTech\FilamentTicketing\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SGCompTech\FilamentTicketing\Interfaces\HasTickets;
use SGCompTech\FilamentTicketing\Traits\InteractsWithTickets;

class Item extends Model implements HasTickets
{
    use HasFactory, InteractsWithTickets;

    protected $guarded = [];
}