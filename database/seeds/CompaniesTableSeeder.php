<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('companies')->delete();
        $faker = Faker::create();
        factory(\App\Models\Company::class, 3)->create()->each(function ($company) use ($faker) {
            factory(\App\Models\User::class, $faker->numberBetween(1, 5))->create([
                'company_id' => $company->id
            ]);
        });
        $company = \App\Models\Company::first();
        $company->alias='citynow';
        $company->save();
    }
}
