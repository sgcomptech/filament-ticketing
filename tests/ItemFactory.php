<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
