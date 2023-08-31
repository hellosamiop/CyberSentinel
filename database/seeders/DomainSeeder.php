<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Domain::create([
            'name' => 'Test',
            'domain_url' => 'https://public-firing-range.appspot.com',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
    }
}
