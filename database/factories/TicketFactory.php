<?php
namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'subject'     => $this->faker->sentence(4),
            'message'     => $this->faker->paragraph(),
            'status'      => 'new',
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}