<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return array
     */
    public function run()
    {
        if (!User::all()->count()) {
            return factory(User::class, 1)->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('secret'),
                'remember_token' => str_random(10),
            ]);
        }

        return factory(User::class, 50)->create();
    }
}
