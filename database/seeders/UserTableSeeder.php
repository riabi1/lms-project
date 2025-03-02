<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class UserTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('users')->insert([
      // Admin 


      [
        'id' => 1,
        'name' => 'Admin',
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'email_verified_at' => null,
        'password' => Hash::make('111'),
        'photo' => '202308172154121859823-male-avatar-icon-or-portrait-handsome-young-man-face-with-beard-vector-illustration-.jpg',
        'phone' => '0112',
        'address' => 'usa',
        'role' => 'admin',
        'status' => '1',
        'last_seen' => '2025-02-26 09:37:03',
        'remember_token' => null,
        'created_at' => null,
        'updated_at' => '2025-02-26 08:37:03',
      ],
      [
        'id' => 2,
        'name' => 'Instructor',
        'username' => 'instructor',
        'email' => 'instructor@gmail.com',
        'email_verified_at' => null,
        'password' => Hash::make('111'),
        'photo' => '202308182056ariyan.jpg',
        'phone' => '0144',
        'address' => 'tunisia',
        'role' => 'instructor',
        'status' => '1',
        'last_seen' => '2023-10-05 21:20:41',
        'remember_token' => null,
        'created_at' => '2023-09-02 20:40:25',
        'updated_at' => '2023-10-05 15:20:41',
      ],
      [
        'id' => 3,
        'name' => 'User',
        'username' => 'user',
        'email' => 'user@gmail.com',
        'email_verified_at' => null,
        'password' => Hash::make('111'),
        'photo' => '202308192225handsome-young-man-with-new-stylish-haircut_176420-19637.jpg',
        'phone' => '012233',
        'address' => 'france',
        'role' => 'user',
        'status' => '1',
        'last_seen' => '2023-10-02 21:26:48',
        'remember_token' => null,
        'created_at' => null,
        'updated_at' => '2023-10-02 15:26:48',
      ],
      [
        'id' => 4,
        'name' => 'eya',
        'username' => null,
        'email' => 'eya@gmail.com',
        'email_verified_at' => null,
        'password' => Hash::make('111'),
        'photo' => null,
        'phone' => null,
        'address' => null,
        'role' => 'user',
        'status' => '1',
        'last_seen' => '2023-10-05 20:38:03',
        'remember_token' => null,
        'created_at' => '2023-08-15 13:38:19',
        'updated_at' => '2023-10-05 14:38:03',
      ],
      [
        'id' => 5,
        'name' => 'dali',
        'username' => 'dali',
        'email' => 'dali@gmail.com',
        'email_verified_at' => null,
        'password' => Hash::make('111'),
        'photo' => '202308192219download.jpg',
        'phone' => '5566',
        'address' => 'Uttara Dhaka',
        'role' => 'user',
        'status' => '1',
        'last_seen' => '2023-10-05 20:24:02',
        'remember_token' => null,
        'created_at' => '2023-08-19 14:35:29',
        'updated_at' => '2023-10-05 14:24:02',
      ],
      

    ]);
  }
}
