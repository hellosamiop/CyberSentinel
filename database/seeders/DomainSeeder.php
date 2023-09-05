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
            'name' => 'Public Firing Range',
            'domain_url' => 'https://public-firing-range.appspot.com',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'PWC',
            'domain_url' => 'https://www.pwc.com.au',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'Countplus',
            'domain_url' => 'https://www.countplus.com.au',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'Findex',
            'domain_url' => 'https://www.findex.com.au',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'BMO',
            'domain_url' => 'https://www.bmo.com.au',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'KPMG',
            'domain_url' => 'https://kpmg.com',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
    }
}
