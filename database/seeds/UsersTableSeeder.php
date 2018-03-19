<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user= new \App\Models\User();
        $user->fill([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
            'company_id' => \App\Models\Company::first()->id,
        ]);
        $user->save();
    }
}
