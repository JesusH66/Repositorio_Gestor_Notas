<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),'title' => fake()->sentence(3),'content' => fake()->paragraphs(3, true),
        ];
    }

    //Para que la nota sea para un usuario en especifico
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    //El título de la nota debe ser corto
    public function shortTitle(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => fake()->words(2, true),
        ]);
    }

    //Para que la nota sea larga
    public function longContent(): static
    {
        return $this->state(fn (array $attributes) => [
            'content' => fake()->paragraphs(10, true),
        ]);
    }
}
