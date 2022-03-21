<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Events;

class EventsFactory extends Factory
{
    protected $model = Events::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_name' => $this->faker->country(),
            'take_photo' => $this->faker->randomElement([0,1]),
            'take_location' => $this->faker->randomElement([0,1]),
            'take_time' => $this->faker->randomElement([0,1]),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_date' => $this->faker->dateTimeBetween('+2 week', '+3 week'),
            'user_id' => $this->faker->randomElement([1,2]),
            'token' => strtoupper($this->faker->bothify('???????')),
        ];
    }
}
