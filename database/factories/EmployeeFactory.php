<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'contact_no' => 9498017460,
            'designation' => fake()->randomElement(['HR', 'BA', 'Senior Software Engineer', 'Software Engineer', 'Senior Software Tester', 'Software Tester', 'Team Lead']),
            'skill' => fake()->randomElement(['Frontend Developer', 'Backend Developer', 'Fullstack Developer']),
            'total_experience' => 10,
            'relevant_experience' => 10,
            'current_ctc' => 2000000,
            'expected_ctc' => 9000000,
            'last_reason_resignation' => fake()->randomElement(['Career Growth', 'Salary Problem', 'Health Issue', 'Personal Reason']),
            'location' => fake()->randomElement(['Chennai', 'Madurai', 'Selam', 'Trichy', 'Karur', 'Kanyakumari', 'Nagarkovil', 'Erode', 'Namakal', 'Sivakasi', 'Sivagankai']),
            'notice_period'=> '10 days',
            'profile'=> fake()->imageUrl()
        ];
    }
}
