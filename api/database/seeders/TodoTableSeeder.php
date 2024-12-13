<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Todo::query()->truncate();

        $data = [
            [
                'title' => 'Rửa bát',
                'is_complete' => true
            ],
            [
                'title' => 'Quét nhà',
                'is_complete' => false
            ],
            [
                'title' => 'Giặt quần áo',
                'is_complete' => false
            ],
        ];

        foreach ($data as $item) {
            Todo::create($item);
        }
    }
}
