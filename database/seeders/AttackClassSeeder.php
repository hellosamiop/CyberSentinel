<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttackClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            [
                's' => 'S1',
                'class' => 'Business Disruption',
            ],
            [
                's' => 'S2',
                'class' => 'Website Hijack',
            ],
            [
                's' => 'S3',
                'class' => 'Data Hijack',
            ],
            [
                's' => 'S4',
                'class' => 'Credentials Hijack',
            ],
            [
                's' => 'S5',
                'class' => 'Client Session Attack',
            ],
            [
                's' => 'S6',
                'class' => 'Social Engineering Attack',
            ],
        ];
        foreach ($classes as $class) {
            DB::table('attack_classes')->insert($class);
        }
    }
}
