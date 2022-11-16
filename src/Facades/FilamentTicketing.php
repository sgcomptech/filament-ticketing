<?php

namespace Sgcomptech\FilamentTicketing\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sgcomptech\FilamentTicketing\FilamentTicketing
 */
class FilamentTicketing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sgcomptech\FilamentTicketing\FilamentTicketing::class;
    }
}
