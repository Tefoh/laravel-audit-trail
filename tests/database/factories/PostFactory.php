<?php

namespace Tofiq\AuditTrail\Tests\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tofiq\AuditTrail\Tests\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph(5),
            'published_at' => Carbon::now(),
        ];
    }
}
