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
                'is_active' => false,
                'created_by' => 1,
                'categories' => []
            ],
            [
                'title' => 'Test Title 2',
                'slug' => str('Test Title 2')->slug(),
                'content' => 'Test Content 2',
                'is_active' => true,
                'created_by' => 1,
                'categories' => [1]
            ],
            [
                'title' => 'Test Title 3',
                'slug' => str('Test Title 3')->slug(),
                'content' => 'Test Content 3',
                'is_active' => true,
                'created_by' => 1,
                'categories' => [1, 2]
            ]
        ];

        $this->_importWithArray($data);
    }

    private function _importWithArray(array $data): void
    {
        foreach ($data as $datum) {
            $categories = $datum['categories'] ?? [];
            unset($datum['categories']);

            Post::create($datum)
                ->categories()
                ->sync(array_fill_keys($categories, [
                    'created_by' => 1
                ]));
        }
    }
}
