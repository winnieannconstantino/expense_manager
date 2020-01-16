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
        DB::table("users")->delete();
        DB::update("ALTER TABLE users AUTO_INCREMENT = 1;");
        $_users = array(
            [
                "user_name" => "Sample User 1",
                "password" => "12345",
                "email" => "admin@gmail.com",
                "role_id" => 1
            ],
            [
                "user_name" => "Sample User 2",
                "password" => "12345",
                "email" => "user@gmail.com",
                "role_id" => 2
            ]
        );
        //DB::table("tbl_amenities")->insert($amenities);
        foreach ($_users as $_user){
            App\Users::create($_user);
        }
    }
}
