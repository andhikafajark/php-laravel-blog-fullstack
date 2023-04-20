<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Technology',
                'slug' => str('Technology')->slug(),
                'type' => 'blog',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Animal',
                'slug' => str('Animal')->slug(),
                'type' => 'blog',
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        $this->_importWithArray($data);
    }

    private function _importWithArray(array $data): void
    {
        foreach ($data as $datum) {
            Category::create($datum);
        }
    }
}
