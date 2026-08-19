<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'user_id' => $this->getUserId(),
            'project_id' => $this->getProjectId(),
            'name' => $this->faker->word(),
            'priority' => Task::getDefaultPriority(),
        ];
    }

    /**
     * Get a project ID.
     *
     * @return ?int
     */
    public function getProjectId(): ?int
    {
        if (!$this->faker->boolean()) { // Decide at random if tasks has a user or not
            return null;
        }
        if (Project::query()->count()) {
            return Project::first()->id;
        } else {
            return Project::factory()->create()->id;
        }
    }

    /**
     * Get a user ID.
     *
     * @return ?int
     */
    public function getUserId(): ?int
    {
        if (!$this->faker->boolean()) { // Decide at random if tasks has a project or not
            return null;
        }
        if (User::query()->count()) {
            return User::first()->id;
        } else {
            return User::factory()->create()->id;
        }
    }
}
