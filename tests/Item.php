<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sgcomptech\FilamentTicketing\Interfaces\HasTickets;
use Sgcomptech\FilamentTicketing\Traits\InteractsWithTickets;

class Item extends Model implements HasTickets
{
    use HasFactory;
    use InteractsWithTickets;

    protected $guarded = [];

    protected static function newFactory()
    {
        return ItemFactory::new();
    }
}
