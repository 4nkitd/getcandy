<?php

namespace GetCandy\Shipping\Database\Factories;

use GetCandy\Shipping\Models\ShippingZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingZoneFactory extends Factory
{
    protected $model = ShippingZone::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => 'postcodes',
        ];
    }
}