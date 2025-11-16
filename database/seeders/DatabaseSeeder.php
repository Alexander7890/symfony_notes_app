<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        if ($groups->isEmpty()) {
            $groups = Group::insert([
                ['name' => 'Work'],
                ['name' => 'Friends'],
                ['name' => 'Family'],
            ]);

            $groups = Group::all();
        }

        Contact::factory()->count(10)->create();
    }
}
