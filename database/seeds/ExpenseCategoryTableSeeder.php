<?php

use Illuminate\Database\Seeder;

class ExpenseCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("expense_categories")->delete();
        DB::update("ALTER TABLE expense_categories AUTO_INCREMENT = 1;");
        $_expense_categories = array(
            [
                "display_name" => "Travel",
                "description" => "Daily Commute"
            ],
            [
                "display_name" => "Entertainment",
                "description" => "Movies, etc."
            ]
        );
        //DB::table("tbl_amenities")->insert($amenities);
        foreach ($_expense_categories as $_expense_category){
            App\Expense_Categories::create($_expense_category);
        }
    }
}
