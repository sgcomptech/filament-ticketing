<?php

namespace SGCompTech\FilamentTicketing\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}