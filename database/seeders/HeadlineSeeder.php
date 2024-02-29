<?php

namespace Database\Seeders;

use App\Models\Headline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headline = new Headline([
            'title' => 'Hi mom!',
        ]);
        $headline->save();
    }
}
