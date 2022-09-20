<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'client_id' => Client::all()->random()->id,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'mobile_no' => $this->faker->regexify('[0-9]{10}'),
            'mobile_no_verified_at' => now(),
            'password' =>  bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'status' =>  'Active',
        ];
    }
}
