<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthClassSeeder extends Seeder
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
                's' => 'H1',
                'class' => 'Code',
            ],
            [
                's' => 'H2',
                'class' => 'Configuration',
            ],
            [
                's' => 'H3',
                'class' => 'Maintenance',
            ],
            [
                's' => 'H4',
                'class' => 'Client',
            ],
            [
                's' => 'H5',
                'class' => '3rd Party',
            ],
            [
                's' => 'H6',
                'class' => 'Social',
            ],
        ];
        foreach ($classes as $class) {
            DB::table('health_classes')->insert($class);
        }
    }
}
