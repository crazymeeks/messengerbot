<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'value' => 'Manage Catalog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => 'Manage Order',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
