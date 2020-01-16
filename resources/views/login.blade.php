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
		background-image: url("{{url('resources/images/chruch.png')}}");
		padding-top: 5%;
	}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4 offset-md-4">
				<div class="card" style="">
					<div class="card-body">
						<h5 class="card-title" style="text-align: center;">Expense Manager</h5>
						<h6 class="card-subtitle mb-2 text-muted" style="text-align: center;">Login</h6>
						<hr>
						<form>
							<div class="form-group">
								<label for="txtEmail">Email</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="far fa-envelope"></i></div>
									</div>
									<input type="email" class="form-control" id="txtEmail" aria-describedby="emailHelp" required>
									<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
								</div>
							</div>
							<div class="form-group">
								<label for="txtPassword">Password</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fas fa-lock"></i></div>
									</div>
									<input type="password" class="form-control" id="txtPassword" required>
								</div>
							</div>
							<button type="button" class="btn btn-primary w-100" id="btnLogin">Login</button>
						</form>
					</div>
				</div>
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
		setTimeout(function(){$('#modal-loader').modal('hide');},1000);
	});

	$(document).ready(function(){

		$("#btnLogin").click(function(event){
			var _email = $("#txtEmail").val();
			var _password = $("#txtPassword").val();

			var _errorMessage = "";
			if(_email == ""){
				_errorMessage += "<i class='fas fa-times' style='padding-right: 10px;'></i>Email is required.<br>";
			}

			if(_password == ""){
				_errorMessage += "<i class='fas fa-times' style='padding-right: 10px;'></i>Password is required.<br>";
			}

			if(_errorMessage != ""){
				$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
				$("#msgModalHeader").addClass("error");
				$("#msgModalTitle").text("Opppss!");
				$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br>"+_errorMessage);
			}
			else{
				$.ajax({
					type : "POST",
					url: "validate-login",
					data: { _email: _email, _password: _password },
					dataType: "JSON",
                    headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
                    success : function(msg){
                    	if(msg.status_code == 0){
                    		$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
							$("#msgModalHeader").addClass("error");
							$("#msgModalTitle").empty().html("Opppss!");
							$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
                    	}
                    	else if(msg.status_code == 1){
                    		window.location.href = "{{url('/')}}";
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
			}
		});

	});
	</script>
</body>
</html>