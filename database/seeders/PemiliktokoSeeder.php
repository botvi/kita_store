<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PemiliktokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'pemiliktoko',
            'name' => 'pemiliktoko',
            'role' => 'pemiliktoko',
            'email' => 'pemiliktoko@gmail.com',
            'foto_profile' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
            'no_wa' => '082211111111',
            'password' => Hash::make('password'), // Pastikan mengganti 'password' dengan kata sandi yang aman
        ]);
    }
}