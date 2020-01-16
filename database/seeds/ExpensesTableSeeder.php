<?php

use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("expenses")->delete();
        DB::update("ALTER TABLE expenses AUTO_INCREMENT = 1;");
        $_expenses = array(
            [
                "category_id" => 1,
                "amount" => "300.00",
                "entry_date" => date("Y-m-d")/*,
                "user_id" => 1*/
            ],
            [
                "category_id" => 2,
                "amount" => "120.25",
                "entry_date" => date("Y-m-d")/*,
                "user_id" => 1*/
            ],
            [
                "category_id" => 1,
                "amount" => "50.00",
                "entry_date" => date("Y-m-d")/*,
                "user_id" => 2*/
            ]
        );
        //DB::table("tbl_amenities")->insert($amenities);
        foreach ($_expenses as $_expense){
            App\Expenses::create($_expense);
        }
    }
}
