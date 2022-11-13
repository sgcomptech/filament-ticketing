<?php

namespace SGCompTech\FilamentTicketing\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SGCompTech\FilamentTicketing\FilamentTicketing
 */
class FilamentTicketing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SGCompTech\FilamentTicketing\FilamentTicketing::class;
    }
}
