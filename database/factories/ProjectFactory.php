<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_no' => $this->faker->unique()->randomNumber(6),
            'project_start_date' => $this->faker->date(),
            'project_delivery_date' => $this->faker->date(),
            'project_enroll_by' => $this->faker->name,
            'domain' => $this->faker->domainName,
            'domain_provider' => $this->faker->company,
            'domain_by' => $this->faker->name,
            'domain_purchase_by' => $this->faker->name,
            'domain_purchase_date' => $this->faker->date(),
            'domain_expire_date' => $this->faker->date(),
            'hosting' => $this->faker->word,
            'hosting_provider' => $this->faker->company,
            'hosting_by' => $this->faker->name,
            'hosting_purchase_by' => $this->faker->name,
            'hosting_purchase_date' => $this->faker->date(),
            'hosting_expire_date' => $this->faker->date(),
            'client' => $this->faker->name,
            'client_mobile_1' => $this->faker->phoneNumber,
            'client_mobile_2' => $this->faker->phoneNumber,
            'client_type' => $this->faker->randomElement(['New', 'Existing']),
            'client_email' => $this->faker->email,
            'client_address' => $this->faker->address,
            'assistant_mobile_1' => $this->faker->phoneNumber,
            'assistant_mobile_2' => $this->faker->phoneNumber,
            'package_price' => $this->faker->randomFloat(2, 100, 1000),
            'renew_price' => $this->faker->randomFloat(2, 50, 500),
            'note' => $this->faker->sentence,
            'is_active' => 1,
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
