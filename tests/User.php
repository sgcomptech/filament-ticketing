<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Filament\Models\Contracts\FilamentUser;

class User extends Model implements AuthorizableContract, AuthenticatableContract, FilamentUser
{
    use Authorizable, Authenticatable, HasFactory;

    protected $guarded = [];

    protected $table = 'users';

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function canAccessFilament(): bool
    {
        return true;
    }
}