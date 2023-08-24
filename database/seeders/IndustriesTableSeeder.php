<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            'Agriculture',
            'Arts',
            'Construction',
            'Consumer Goods',
            'Corporate Services',
            'Design',
            'Education',
            'Energy & Mining',
            'Entertainment',
            'Finance',
            'Hardware & Networking',
            'Health Care',
            'Legal',
            'Manufacturing',
            'Media & Communications',
            'Nonprofit',
            'Public Administration',
            'Public Safety',
            'Real Estate',
            'Recreation & Travel',
            'Retail',
            'Software & IT Services',
            'Transportation & Logistics',
            'Wellness & Fitness',
        ];

        foreach ($industries as $industry) {
            DB::table('industries')->insert([
                'name' => $industry,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
