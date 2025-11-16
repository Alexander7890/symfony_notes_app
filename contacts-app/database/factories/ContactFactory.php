<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'note' => $this->faker->optional()->realText(80),
            'group_id' => Group::factory(),
        ];
    }
}
