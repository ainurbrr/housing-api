<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin  Kelurahan',
            'email' => 'kelurahan@gmail.com',
        ]);

        
        Resident::factory(20)->create();
        House::factory(20)->create();
        Payment::factory(30)->create();
    }
}
