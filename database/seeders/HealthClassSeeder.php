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
                'h' => 'H1',
                'class' => 'Code',
            ],
            [
                'h' => 'H2',
                'class' => 'Configuration',
            ],
            [
                'h' => 'H3',
                'class' => 'Maintenance',
            ],
            [
                'h' => 'H4',
                'class' => 'Client',
            ],
            [
                'h' => 'H5',
                'class' => '3rd Party',
            ],
            [
                'h' => 'H6',
                'class' => 'Social',
            ],
        ];
        foreach ($classes as $class) {
            DB::table('health_classes')->insert($class);
        }
    }
}
