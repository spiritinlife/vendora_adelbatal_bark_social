<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class BarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'message' => $this->randomBarking(),
            'created_at' => fake()->unixTime(),
        ];
    }

    protected function randomBarking(): string
    {
        $barkTimes = rand(1, 10);
        $barks = [];
        for ($i=0; $i < $barkTimes; $i++) {
            $barks []= 'Bark';
        }

        return implode(' ', $barks);
    }

}
