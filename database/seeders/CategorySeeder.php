<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Product',
                'description' => 'hair care products',
            ],
            [
                'category_name' => 'Service',
                'description' => 'hair cutting and styling services',
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
