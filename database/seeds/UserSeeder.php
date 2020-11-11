<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Mohamed sabry',
                'username' => 'mohamedsabry',
                'email' => 'mohamedelnagar1@yahoo.com',
                'password' => Hash::make('123456789'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Mohamed Elsayed',
                'username' => 'mohamedelsayed',
                'email' => 'mohamedelnagar2@yahoo.com',
                'password' => Hash::make('123456789'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Mohamed Osama',
                'username' => 'mohamedosama',
                'email' => 'mohamedelnagar3@yahoo.com',
                'password' => Hash::make('123456789'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ]);

    }
}
