<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(12)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();

        // Luis Rojas usuario
        User::create(
            [
                'name' => 'Luis Rojas',
                'email' => 'lrojas@justierradelfuego.gov.ar',
                'email_verified_at' => now(),
                //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  //password
                'password' => '$2a$12$BSycJGxQJUmz2rSyn9Wv2.BpmKRV3Fjfw2DzEPHlKF4dDULvUjiPS', //123
                'remember_token' => Str::random(10),
            ]

        );

        User::create(
            [
                'name' => 'Marcelo Arinzabarreta',
                'email' => 'mariznabarreta@justierradelfuego.gov.ar',
                'email_verified_at' => now(),
                //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  //password
                'password' => '$2a$12$BSycJGxQJUmz2rSyn9Wv2.BpmKRV3Fjfw2DzEPHlKF4dDULvUjiPS', //123
                'remember_token' => Str::random(10),
            ]
        );

        User::create(
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  //password
                'password' => '$2a$12$BSycJGxQJUmz2rSyn9Wv2.BpmKRV3Fjfw2DzEPHlKF4dDULvUjiPS', //123
                'remember_token' => Str::random(10),
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
}
