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
            'name' => 'HackThisSite',
            'domain_url' => 'https://www.hackthissite.org',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'VulnWeb',
            'domain_url' => 'http://testhtml5.vulnweb.com/',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'Countplus',
            'domain_url' => 'http://testasp.vulnweb.com/',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'OWASP',
            'domain_url' => 'https://blog.dinosec.com/2013/11/owasp-vulnerable-web-applications.html?m=1',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);
        Domain::create([
            'name' => 'Qalam',
            'domain_url' => 'https://qalam.nust.edu.pk/',
            'industry_id' => 1,
            'country_id' => 1,
            'user_id' => 1,
        ]);

    }
}
