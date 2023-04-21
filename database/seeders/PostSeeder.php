<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Test Title 1',
                'slug' => str('Test Title 1')->slug(),
                'content' => 'Test Content 1',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Test Title 2',
                'slug' => str('Test Title 2')->slug(),
                'content' => 'Test Content 2',
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        $this->_importWithArray($data);
    }

    private function _importWithArray(array $data): void
    {
        foreach ($data as $datum) {
            Post::create($datum);
        }
    }
}
