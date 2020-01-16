<html>
<head>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta content='True' name='HandheldFriendly' />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="stylesheet" type="text/css" href="{{url('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('bootstrap/css/custom-modal.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('bootstrap/css/font-awesome/all.min.css')}}">
	<script type="text/javascript" src="{{url('bootstrap/js/jquery-3.4.1.min.js')}}"></script>
	<script type="text/javascript" src="{{url('bootstrap/js/bootstrap.min.js')}}"></script>

	<title>{!! $_title !!}</title>

	<style type="text/css">
	body{
		height: 100%;
		background-color: white;
	}
	.align-right{
		text-align: right;
	}
	.hideObj{
		display: none;
	}
	</style>
</head>
<body>
	<div class="container-fluid" style="height: 100%;">
		<div class="row" style="height: 100%;">
			<div class="col-md-2 h-100" style="background-color: #0069d9;border-color: #0062cc;color:white; height: 100vh; padding: 0px;">
				<div style="background-color: #0257b3; padding: 25px 15px;">
					<div style="height: 50px; width: 50px; background-color: #fff; border-radius: 50%; margin-bottom: 15px;"></div>
					<label>{!! Session::get("user_details")[0]["user_name"] !!}</label>
					<small>({!! Session::get("user_details")[0]["role"] !!})</small>
				</div>
				<div>
					<div class="list-group">
						<a href="{{url('/')}}" class="list-group-item list-group-item-action {{ Request::path() === '/' ? 'active' : ''}}">
							Dashboard
						</a>
						<a href="#" class="list-group-item list-group-item-action disabled">
							User Management
						</a>
						<a href="{{url('user-management/roles')}}" class="list-group-item list-group-item-action {{ Request::path() === 'user-management/roles' ? 'active' : ''}}" style="padding-left: 45px;">
							Roles
						</a>
						<a href="{{url('user-management/users')}}" class="list-group-item list-group-item-action {{ Request::path() === 'user-management/users' ? 'active' : ''}}" style="padding-left: 45px;">
							Users
						</a>
						<a href="#" class="list-group-item list-group-item-action disabled">
							Expense Management
						</a>
						<a href="{{url('expense-management/categories')}}" class="list-group-item list-group-item-action {{ Request::path() === 'expense-management/categories' ? 'active' : ''}}" style="padding-left: 45px;">
							Expense Categories
						</a>
						<a href="{{url('expense-management/expenses')}}" class="list-group-item list-group-item-action {{ Request::path() === 'expense-management/expenses' ? 'active' : ''}}" style="padding-left: 45px;">
							Expenses
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-10" style="padding: 0px;">
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<div class="collapse navbar-collapse" id="navbarText">
						<ul class="navbar-nav" style="margin-left: auto !important;">
							<li class="nav-item">
								<a class="nav-link disabled" style="padding-right: 25px;">Welcome to Expense Manager</a>
							</li>
							<li class="nav-item {{ Session::get('user_details')[0]['role'] === 'Administrator' ? 'hideObj' : ''}}">
								<a class="nav-link" style="padding-right: 25px;" href="" data-toggle="modal" data-target="#change-password">Change Password</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{url('/logout')}}">Logout</a>
							</li>
						</ul>
					</div>
				</nav>
				<br><br>
				@yield('contents')
			</div>
		</div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="msg-modal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header" id="msgModalHeader">
					<h5 class="modal-title" id="msgModalTitle"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="msgModalBody">
					
				</div>
			</div>
		</div>
	</div>
	<div class="modal" tabindex="-1" role="dialog" id="change-password">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Change Password</h5>
				</div>
				<div class="modal-body">
					<div class="alert alert-danger alert-dismissible fade show hideObj" role="alert" id="errorMsg_alert">
						<label id="errorMsg"></label>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form>
						<div class="form-group">
							<label for="txtOldPass">Old Password</label>
							<input type="password" class="form-control" id="txtOldPass">
						</div>
						<div class="form-group">
							<label for="txtNewPass">New Password</label>
							<input type="password" class="form-control" id="txtNewPass" required>
						</div>
						<div class="form-group">
							<label for="txtNewPassConf">Confirm Password</label>
							<input type="password" class="form-control" id="txtNewPassConf" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
					<button type="button" class="btn btn-primary" id="btnUpdatePassword">Change Password</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-loader" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body" style="text-align: center">
					<div class="spinner-grow" role="status">
						<span class="sr-only"></span>
					</div>
					<div class="spinner-grow" role="status">
						<span class="sr-only"></span>
					</div>
					<div class="spinner-grow" role="status">
						<span class="sr-only"></span>
					</div>
					<p style="text-align: center;">Loading ...</p>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	$( document ).ajaxStart(function() {
		$('#modal-loader').modal({keyboard: false, backdrop: 'static'}).modal("show");
	});	
	$(document).ajaxComplete(function (event, xhr, settings) {
		setTimeout(function(){$('#modal-loader').modal('hide');},500);
	});
	$(document).ready(function(){
		$("#btnUpdatePassword").click(function(){
			$.ajax({
				type : "POST",
				url: "{{url('change-password')}}",
				data: { _oldPass : $("#txtOldPass").val(), _newPass : $("#txtNewPass").val(), _newPassConf : $("#txtNewPassConf").val() },
				dataType: "JSON",
				headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
				success : function(msg){
					if(msg.status_code == -1){
						$("#errorMsg").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
						$("#errorMsg_alert").removeClass("hideObj");
					}
					else if(msg.status_code == 0){
						$("#errorMsg").empty().html(msg.msg);
						$("#errorMsg_alert").removeClass("hideObj");
					}
					else if(msg.status_code == 1){
						window.location.href = "{{url('/logout')}}";;
					}
					else{
						$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
						$("#msgModalHeader").addClass("error");
						$("#msgModalTitle").empty().html("Opppss!");
						$("#msgModalBody").addClass("error").empty().html(msg.msg);
					}
				},
				error : function(jqXHR, textStatus, errorThrown){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the status: <i>" + textStatus + "</i><br><br>"+errorThrown);
				}
			});
});
});
</script>
</body>
</html>