<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function findOrCreateByContacts(string $email, string $phone, string $name): Customer
    {
        // чтобы не плодить дублей клиентов
        return Customer::updateOrCreate(
            ['email' => $email, 'phone' => $phone],
            ['name' => $name]
        );
    }
}
