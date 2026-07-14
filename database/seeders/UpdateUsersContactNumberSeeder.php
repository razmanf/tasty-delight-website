<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UpdateUsersContactNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $users = User::where('role', '!=', 'admin')->get();
        $usedNumbers = [];

        foreach ($users as $user) {
            $number = null;
            do {
                // Sri Lankan number starts with 0 and has 10 digits
                $number = '0' . $faker->numerify('#########');
            } while (in_array($number, $usedNumbers));
            
            $usedNumbers[] = $number;
            
            $user->contact_number = $number;
            $user->save();
        }
    }
}
