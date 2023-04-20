<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'created_by' => 1
            ]
        ];

        $this->_importWithArray($data);
    }

    private function _importWithArray(array $data): void
    {
        foreach ($data as $datum) {
            User::create($datum);
        }
    }
}
