<?php
use App\Roles;
use App\Users;
use App\Expense_Categories;
use App\Expenses;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
	if(!Session::has("user_details")){
		return redirect("/login");
	}

	//dd(Session::get("user_details"));

	$_expenseCategories = json_decode(json_encode(Expense_Categories::select('category_id', 'display_name as expense_category')->get()), true);

	$_trItems = "";
	$_pieItems = array();

	if(sizeof($_expenseCategories) > 0){

		//$_totalExpenses = Expenses::where("user_id", Session::get("user_details")[0]["user_id"])->sum('amount');
		$_totalExpenses = Expenses::sum('amount');
		
		array_push($_pieItems, array("Categories", "Total"));

		for($_ctr = 0; $_ctr < sizeof($_expenseCategories); $_ctr++){
			//$_totalExpensesPerCategory = Expenses::where("expense_category", $_expenseCategories[$_ctr]["expense_category"])->where("user_id", Session::get("user_details")[0]["user_id"])->sum("amount");
			$_totalExpensesPerCategory = Expenses::where("category_id", $_expenseCategories[$_ctr]["category_id"])->sum("amount");
			$_trItems .= "<tr><td>" . $_expenseCategories[$_ctr]["expense_category"] . "</td><td class='align-right'>" . $_totalExpensesPerCategory . "</td></tr>";

			/*$_pieItems .= "<div class='pie__segment' style='--offset: 0; --value: " . round((($_totalExpensesPerCategory / $_totalExpenses) * 100)) . "; --bg: #" . substr(md5(mt_rand()), 0, 6) . "; --tan: " . tan(round((($_totalExpensesPerCategory / $_totalExpenses) * 100))) . "; ";
			if($_ctr == 0){
				$_pieItems .= "--over50: 1;";
			}
			$_pieItems .= "'></div>";*/

			array_push($_pieItems, array($_expenseCategories[$_ctr]["expense_category"], $_totalExpensesPerCategory));
		}
	}
	else{
		$_trItems = "<tr><td colspan='2'>No record found!</td></tr>";
	}

	return view("dashboard", ["_title" => "Expense Manager | Dashboard", "_trItems" => $_trItems, "_pie" => $_pieItems]);
});

Route::get("login", function () {
	return view("login", ["_title" => "Expense Manager"]);
});

Route::post("validate-login", function () {
	try{
		$_validator = Validator::make(
			[
			"email" => trim(Request::get("_email")),
			"password" => trim(Request::get("_password"))
			],
			[
			"email" => "required|email",
			"password" => "required"
			]
			);

		if ($_validator->fails()) {
			$_errMsgs = $_validator->messages();
			return json_encode(array("status_code" => 0, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
		}

		$_user = DB::table("users")
					->select("users.user_id", "users.user_name", "users.password", "users.email", "users.role_id", "users.created_at", "roles.display_name as role")
					->join('roles', 'users.role_id', '=', 'roles.role_id')
					->where("email", pg_escape_string(trim(Request::get("_email"))))
					->where("password", pg_escape_string(trim(Request::get("_password"))))
					->get();

		if(sizeof($_user) > 0){
			$_userDtls = json_decode(json_encode($_user), true);
			Session::put("user_details", $_userDtls);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}
		else{
			return json_encode(array("status_code" => -1, "msg" => "No record found!"));
		}
	}
	catch(Exception $_ex){
		return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
	}
});

Route::get("logout", function () {
	Session::flush();
	return redirect("/login");
});

Route::post("change-password", function () {
	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"old password" => trim(Request::get("_oldPass")),
		"new password" => trim(Request::get("_newPass")),
		"confirm password" => trim(Request::get("_newPassConf"))
		],
		[
		"old password" => "required",
		"new password" => "required",
		"confirm password" => "required"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	$_user = json_decode(json_encode(Users::where("user_id", Session::get("user_details")[0]["user_id"])->where("password", pg_escape_string(trim(Request::get("_oldPass"))))->get()), true);

	if(sizeof($_user) > 0){
		if(trim(Request::get("_newPass")) === trim(Request::get("_newPassConf"))){
			Users::where("user_id", pg_escape_string(Session::get("user_details")[0]["user_id"]))->update(['password' => pg_escape_string(trim(Request::get("_newPass")))]);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}
		else{
			return json_encode(array("status_code" => 0, "msg" => "New password didn't match."));
		}
	}
	else{
		return json_encode(array("status_code" => 0, "msg" => "Incorrect old password."));
	}
});


Route::get("user-management/{item}", function ($_itemRequest) {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_tableDetails = "";
	$_slctOption = "";
	if(Session::get("user_details")[0]["role"] === "Administrator"){
		if($_itemRequest === "roles"){
			$_roles = json_decode(json_encode(Roles::all()), true);
			for ($_ctr=0; $_ctr < sizeof($_roles); $_ctr++) { 
				$_tableDetails .= "<tr><td style='display: none;'>" . $_roles[$_ctr]["role_id"] . "</td><td>" . $_roles[$_ctr]["display_name"] . "</td><td>" . $_roles[$_ctr]["description"] . "</td><td>" . date("Y-m-d", strtotime($_roles[$_ctr]["created_at"])) . "</td></tr>";
			}
		}
		else if($_itemRequest === "users"){
			//$_users = json_decode(json_encode(Users::all()), true);
			$_users = DB::table("users")
					->select("users.user_id", "users.user_name", "users.password", "users.email", "users.role_id", "users.created_at", "roles.display_name as role")
					->join('roles', 'users.role_id', '=', 'roles.role_id')
					->get();
			$_users = json_decode(json_encode($_users), true);
			for ($_ctr=0; $_ctr < sizeof($_users); $_ctr++) { 
				$_tableDetails .= "<tr><td style='display: none;'>" . $_users[$_ctr]["user_id"] . "</td><td>" . $_users[$_ctr]["user_name"] . "</td><td>" . $_users[$_ctr]["email"] . "</td><td>" . $_users[$_ctr]["role"] . "</td><td>" . date("Y-m-d", strtotime($_users[$_ctr]["created_at"])) . "</td></tr>";
			}
			$_slctOptionRes = json_decode(json_encode(Roles::all()), true);
			for ($_ctr=0; $_ctr < sizeof($_slctOptionRes); $_ctr++) { 
				$_slctOption .= "<option value='" . $_slctOptionRes[$_ctr]["role_id"] . "' text-value='" . $_slctOptionRes[$_ctr]["display_name"] . "'>" . $_slctOptionRes[$_ctr]["display_name"] . "</option>";
			}
		}
		else{
			return redirect("/logout");
		}

		return view("user_management", ["_title" => "Expense Manager | " . ucwords($_itemRequest), "_itemRequest" => ucwords($_itemRequest), "_tableDetails" => $_tableDetails, "_slctOption" => $_slctOption]);
	}
	else{
		return view("user_management", ["_title" => "Expense Manager | " . ucwords($_itemRequest), "_itemRequest" => ucwords($_itemRequest), "_tableDetails" => $_tableDetails, "_slctOption" => $_slctOption]);
	}
});
Route::post("delete-role", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id")),
		"role" => trim(Request::get("_name"))
		],
		[
		"id" => "required|numeric",
		"role" => "required"
		]
	);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	if(trim(Request::get("_id")) === "1"){
		return json_encode(array("status_code" => 0, "msg" => "You can't delete administrator role."));
	}
	else{
		$_deleteRole = Roles::where("role_id", intval(Request::get("_id")))->delete();

		if($_deleteRole > 0){
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}
		return json_encode(array("status_code" => 0, "msg" => "No role found!"));
	}
});
Route::post("update-role", function () {
	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id")),
		"role" => trim(Request::get("_name"))
		],
		[
		"id" => "required|numeric",
		"role" => "required"
		]
	);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	if(Request::get("_id") === "1"){
		return json_encode(array("status_code" => 0, "msg" => "You can't update administrator role."));
	}
	else{
		Roles::where("role_id", intval(Request::get("_id")))->update(['display_name' => pg_escape_string(trim(Request::get("_name"))), 'description' => pg_escape_string(trim(Request::get("_description")))]);
		return json_encode(array("status_code" => 1, "msg" => "success"));
	}
});
Route::post("add-role", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"role name" => trim(Request::get("_name"))
		],
		[
		"role name" => "required"
		]
	);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	$_validateRole = Roles::whereRaw("LOWER(display_name) = '" . pg_escape_string(trim(Request::get("_name"))) . "'")->get();
	
	if(sizeof($_validateRole) > 0){
		return json_encode(array("status_code" => 0, "msg" => "Role already exist! Please choose another role name!"));
	}

	Roles::insert(["display_name" => pg_escape_string(trim(Request::get("_name"))), "description" => pg_escape_string(trim(Request::get("_description"))), "created_at" => date("Y-m-d H:i:s")]);
	
	return json_encode(array("status_code" => 1, "msg" => "success"));
});
Route::post("delete-user", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id"))
		],
		[
		"id" => "required|numeric"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	if(intval(Request::get("_id")) === Session::get("user_details")[0]["user_id"]){
		return json_encode(array("status_code" => 0, "msg" => "You can't delete your own account."));
	}
	else{
		$_deleteUser = Users::where("user_id", intval(Request::get("_id")))->delete();

		if($_deleteUser > 0){
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}
		return json_encode(array("status_code" => 0, "msg" => "No role found!"));
	}
});
Route::post("update-user", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id")),
		"name" => trim(Request::get("_name")),
		"email" => trim(Request::get("_email")),
		"role" => trim(Request::get("_role"))
		],
		[
		"id" => "required|numeric",
		"name" => "required",
		"email" => "required|email",
		"role" => "required|numeric"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	if(Request::get("_id") === "1"){
		return json_encode(array("status_code" => 0, "msg" => "You can't update dafault admin account."));
	}

	Users::where("user_id", intval(Request::get("_id")))->update(['user_name' => pg_escape_string(trim(Request::get("_name"))), 'email' => pg_escape_string(trim(Request::get("_email"))), 'role_id' => Request::get("_role")]);
	return json_encode(array("status_code" => 1, "msg" => "success"));

});
Route::post("add-user", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"name" => trim(Request::get("_name")),
		"email" => trim(Request::get("_email")),
		"role" => trim(Request::get("_role"))
		],
		[
		"name" => "required",
		"email" => "required|email",
		"role" => "required|numeric"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	$_validateUser = Users::whereRaw("LOWER(email) = '" .pg_escape_string(trim(Request::get("_email"))) . "'")->get();
	
	if(sizeof($_validateUser) > 0){
		return json_encode(array("status_code" => 0, "msg" => "User email address already exist!"));
	}

	Users::insert(["user_name" => pg_escape_string(trim(Request::get("_name"))), "email" => pg_escape_string(trim(Request::get("_email"))), "role_id" => Request::get("_role"), "password" => pg_escape_string("12345"), "created_at" => date("Y-m-d H:i:s")]);
	
	return json_encode(array("status_code" => 1, "msg" => "success"));
});


Route::get("expense-management/{item}", function ($_itemRequest) {

	if(!Session::has("user_details")){
		return redirect("/login");
	}
	
	$_tableDetails = "";
	$_slctOption = "";
	if($_itemRequest === "categories"){
		if(Session::get("user_details")[0]["role"] === "Administrator"){
			$_categories = json_decode(json_encode(Expense_Categories::all()), true);
			for ($_ctr=0; $_ctr < sizeof($_categories); $_ctr++) { 
				$_tableDetails .= "<tr><td style='display: none;'>" . $_categories[$_ctr]["category_id"] . "</td><td>" . $_categories[$_ctr]["display_name"] . "</td><td>" . $_categories[$_ctr]["description"] . "</td><td>" . date("Y-m-d", strtotime($_categories[$_ctr]["created_at"])) . "</td></tr>";
			}
		}
	}
	else if($_itemRequest === "expenses"){
		//$_expenses = json_decode(json_encode(Expenses::where("user_id", Session::get("user_details")[0]["user_id"])->get()), true);
		//$_expenses = json_decode(json_encode(Expenses::all()), true);
		$_expenses = DB::table("expenses")
					->select("expenses.expenses_id", "expenses.category_id", "expenses.amount", "expenses.entry_date", "expenses.created_at", "expense_categories.display_name as expense_category")
					->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.category_id')
					->get();
		$_expenses = json_decode(json_encode($_expenses), true);
		for ($_ctr=0; $_ctr < sizeof($_expenses); $_ctr++) { 
			$_tableDetails .= "<tr><td style='display: none;'>" . $_expenses[$_ctr]["expenses_id"] . "</td><td>" . $_expenses[$_ctr]["expense_category"] . "</td><td> $" . $_expenses[$_ctr]["amount"] . "</td><td>" . date("Y-m-d", strtotime($_expenses[$_ctr]["entry_date"])) . "</td><td>" . date("Y-m-d", strtotime($_expenses[$_ctr]["created_at"])) . "</td></tr>";
		}
		$_slctOptionRes = json_decode(json_encode(Expense_Categories::all()), true);
		for ($_ctr=0; $_ctr < sizeof($_slctOptionRes); $_ctr++) { 
			$_slctOption .= "<option value='" . $_slctOptionRes[$_ctr]["category_id"] . "' text-value='" . $_slctOptionRes[$_ctr]["display_name"] . "'>" . $_slctOptionRes[$_ctr]["display_name"] . "</option>";
		}
	}
	else{
		return redirect("/logout");
	}

	return view("expense_management", ["_title" => "Expense Manager | " . ucwords($_itemRequest), "_itemRequest" => ucwords($_itemRequest), "_tableDetails" => $_tableDetails, "_slctOption" => $_slctOption]);
});
Route::post("delete-category", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id"))
		],
		[
		"id" => "required|numeric"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	$_deleteCategory = Expense_Categories::where("category_id", intval(Request::get("_id")))->delete();

	if($_deleteCategory > 0){
		return json_encode(array("status_code" => 1, "msg" => "success"));
	}
	return json_encode(array("status_code" => 0, "msg" => "No role found!"));
});
Route::post("update-category", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id")),
		"category name" => trim(Request::get("_name"))
		],
		[
		"id" => "required|numeric",
		"category name" => "required"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	Expense_Categories::where("category_id", intval(Request::get("_id")))->update(['display_name' => pg_escape_string(trim(Request::get("_name"))), 'description' => pg_escape_string(trim(Request::get("_description")))]);
	return json_encode(array("status_code" => 1, "msg" => "success"));
});
Route::post("add-category", function () {
	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"category name" => trim(Request::get("_name"))
		],
		[
		"category name" => "required"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	$_validateCategory = Expense_Categories::whereRaw("LOWER(display_name) = '" . pg_escape_string(trim(Request::get("_name"))) . "'")->get();
	
	if(sizeof($_validateCategory) > 0){
		return json_encode(array("status_code" => 0, "msg" => "Category was already exist!"));
	}

	Expense_Categories::insert(["display_name" => pg_escape_string(trim(Request::get("_name"))), "description" => pg_escape_string(trim(Request::get("_description"))), "created_at" => date("Y-m-d H:i:s")]);
	return json_encode(array("status_code" => 1, "msg" => "success"));
});
Route::post("delete-expenses", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id"))
		],
		[
		"id" => "required|numeric"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	//$_deleteExpenses = Expenses::where("expenses_id", intval(Request::get("_id")))->where("user_id", Session::get("user_details")[0]["user_id"])->delete();
	$_deleteExpenses = Expenses::where("expenses_id", intval(Request::get("_id")))->delete();

	if($_deleteExpenses > 0){
		return json_encode(array("status_code" => 1, "msg" => "success"));
	}
	return json_encode(array("status_code" => 0, "msg" => "No role found!"));
});
Route::post("update-expenses", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"id" => trim(Request::get("_id")),
		"category id" => trim(Request::get("_category")),
		"amount" => trim(Request::get("_amount")),
		"entry date" => trim(Request::get("_entryDate"))
		],
		[
		"id" => "required|numeric",
		"category id" => "required|numeric",
		"amount" => "required|numeric",
		"entry date" => "required|date"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	/*Expenses::where("expenses_id", intval(Request::get("_id")))->where("user_id", Session::get("user_details")[0]["user_id"])->update([
		'expense_category' => pg_escape_string(trim(Request::get("_category"))), 
		"amount" => Request::get("_amount"), 
		"entry_date" => pg_escape_string(trim(Request::get("_entryDate")))]);*/
Expenses::where("expenses_id", intval(Request::get("_id")))->update([
		'category_id' => pg_escape_string(trim(Request::get("_category"))), 
		"amount" => Request::get("_amount"), 
		"entry_date" => pg_escape_string(trim(Request::get("_entryDate")))]);
	return json_encode(array("status_code" => 1, "msg" => "success"));
});
Route::post("add-expenses", function () {

	if(!Session::has("user_details")){
		return redirect("/login");
	}

	$_validator = Validator::make(
		[
		"expense category" => trim(Request::get("_category")),
		"amount" => trim(Request::get("_amount")),
		"entry date" => trim(Request::get("_entryDate"))
		],
		[
		"expense category" => "required",
		"amount" => "required|numeric",
		"entry date" => "required|date"
		]
		);

	if ($_validator->fails()) {
		$_errMsgs = $_validator->messages();
		return json_encode(array("status_code" => -1, "msg" => json_decode(json_encode($_errMsgs->all()), true)));
	}

	Expenses::insert(['category_id' => pg_escape_string(trim(Request::get("_category"))), "amount" => Request::get("_amount"), "entry_date" => pg_escape_string(trim(Request::get("_entryDate"))),  "created_at" => date("Y-m-d H:i:s")]);
	return json_encode(array("status_code" => 1, "msg" => "success"));
});