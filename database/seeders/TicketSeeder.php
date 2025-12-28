<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        // 1️⃣ Заявки за сегодня
        Ticket::factory()
            ->count(3)
            ->for($customers->random())
            ->state(['status' => 'new'])
            ->create();

        // 2️⃣ Заявки за последнюю неделю
        Ticket::factory()
            ->count(4)
            ->for($customers->random())
            ->state([
                'status' => 'in_progress',
                'created_at' => now()->subDays(5),
            ])
            ->create();

        // 3️⃣ Заявки за прошлый месяц
        Ticket::factory()
            ->count(3)
            ->for($customers->random())
            ->state([
                'status' => 'done',
                'created_at' => now()->subMonth(),
                'answered_at' => now()->subWeeks(2),
            ])
            ->create();
    }
}