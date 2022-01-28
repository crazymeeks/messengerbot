<?php

use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            // Super admin
            [
                'role_id' => 1,
                'firstname' => 'Superadmin',
                'lastname' => null,
                'username' => 'superadmin',
                'email' => 'superadmin@nuworks.ph',
                'password' => bcrypt('W3c@nd0th!s'),
                'status' => 'active'
            ],
            // Admin
            [
                'role_id' => 1,
                'firstname' => 'Michael Hill',
                'lastname' => 'Ng',
                'username' => 'mikeng',
                'email' => 'mike.ng@nuworks.ph',
                'password' => bcrypt('B3tt3r2ged3r'),
                'status' => 'active'
            ]
        ]);
    }
}
