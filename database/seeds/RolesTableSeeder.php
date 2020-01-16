<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("roles")->delete();
        DB::update("ALTER TABLE roles AUTO_INCREMENT = 1;");
        $_roles = array(
            [
                "display_name" => "Administrator",
                "description" => "Super User"
            ],
            [
                "display_name" => "User",
                "description" => "Can add expenses"
            ]
        );
        //DB::table("tbl_amenities")->insert($amenities);
        foreach ($_roles as $_role){
            App\Roles::create($_role);
        }
    }
}
