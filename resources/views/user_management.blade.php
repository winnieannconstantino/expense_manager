@extends('layout.main_layout')

@section('contents')
<div style="padding: 0px 50px;">
	@if(Session::get("user_details")[0]["role"] === "Administrator")
	<div class="row">
		<div class="col-md-5">
			<h4>{!! $_itemRequest !!}</h4>
		</div>
		<div class="col-md-7" style="text-align: right;">
			<label>User Management&nbsp;&nbsp;>&nbsp;&nbsp;{!! $_itemRequest !!}</label>
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-md-12">
			<input type="hidden" id="txtSlctdRowID">
			<table class="table table-hover" id="tblDisplay">
				@if($_itemRequest === 'Roles')
				<thead>
					<tr>
						<th scope="col" style="display: none;"></th>
						<th scope="col">Display Name</th>
						<th scope="col">Description</th>
						<th scope="col">Created at</th>
					</tr>
				</thead>
				@else
				<thead>
					<tr>
						<th scope="col" style="display: none;"></th>
						<th scope="col">Name</th>
						<th scope="col">Email Address</th>
						<th scope="col">Role</th>
						<th scope="col">Create at</th>
					</tr>
				</thead>
				@endif
				<tbody>
					{!! $_tableDetails !!}
				</tbody>
			</table>
			<br>
			@if($_itemRequest === 'Roles')
				<button type="button" class="btn btn-primary float-right" id="btnAddRole">Add Role</button>
			@else
				<button type="button" class="btn btn-primary float-right" id="btnAddUser">Add User</button>
			@endif
		</div>
	</div>
	@else
	<div class="row">
		<div class="col-md-12">
			<h2>You don't have access here.</h2>
		</div>
	</div>
	@endif
</div>
@if($_itemRequest === 'Roles')
<div class="modal" tabindex="-1" role="dialog" id="au-role-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="auRoleModalTitle"></h5>
			</div>
			<div class="modal-body" id="auRoleModalBody">
				<form>
					<div class="form-group">
						<label for="txtDisplayName">Display Name</label>
						<input type="text" class="form-control" id="txtDisplayName">
					</div>
					<div class="form-group">
						<label for="">Description</label>
						<input type="text" class="form-control" id="txtDescription" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger float-left" id="btnDelete" style="">Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-primary" id="btnUpdate">Update</button>
				<button type="button" class="btn btn-primary" id="btnSave">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to delete this role?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesDelete">Yes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-update-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to update this role?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesUpdate">Yes</button>
			</div>
		</div>
	</div>
</div>
@else
<div class="modal" tabindex="-1" role="dialog" id="au-user-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="auUserModalTitle"></h5>
			</div>
			<div class="modal-body" id="auUserModalBody">
				<form>
					<div class="form-group">
						<label for="txtUser">Name</label>
						<input type="text" class="form-control" id="txtUser">
					</div>
					<div class="form-group">
						<label for="txtEmail">Email</label>
						<input type="email" class="form-control" id="txtEmail" required>
					</div>
					<div class="form-group">
						<label for="txtRole">Role</label>
						<select id="slctRoleOption" class="form-control custom-select">
							{!! $_slctOption !!}
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger float-left" id="btnDeleteUser">Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-primary" id="btnUpdateUser">Update</button>
				<button type="button" class="btn btn-primary" id="btnSaveUser">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-user-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to delete this user?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesDeleteUser">Yes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-user-update-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to update this user?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesUpdateUser">Yes</button>
			</div>
		</div>
	</div>
</div>
@endif
<script type="text/javascript">
$(document).ready(function(){

	$("#tblDisplay tr").click(function(event){
		if("{{$_itemRequest}}" == "Roles"){
			$("#auRoleModalTitle").text("Update Role");
			$("#txtSlctdRowID").val($(this).find('td:eq(0)').text());
			$("#txtDisplayName").val($(this).find('td:eq(1)').text());
			//$("#txtDisplayName").attr("disabled", "disabled");
			$("#txtDescription").val($(this).find('td:eq(2)').text());
			$("#btnSave").addClass("hideObj");
			$("#btnDelete").removeClass("hideObj");
			$("#btnUpdate").removeClass("hideObj");

			$("#au-role-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
		}
		else{
			$("#auUserModalTitle").text("Update User");
			$("#txtSlctdRowID").val($(this).find('td:eq(0)').text());
			$("#txtUser").val($(this).find('td:eq(1)').text());
			$("#txtEmail").val($(this).find('td:eq(2)').text());
			$("#slctRoleOption option[text-value="+ $(this).find('td:eq(3)').text() +"]").prop("selected","selected");
			$("#btnSaveUser").addClass("hideObj");
			$("#btnDeleteUser").removeClass("hideObj");
			$("#btnUpdateUser").removeClass("hideObj");

			$("#au-user-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
		}
	});

	$("#btnDelete").click(function(){
		$("#question-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnYesDelete").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('delete-role')}}",
			data: { _id: $("#txtSlctdRowID").val(), _name: $("#txtDisplayName").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-modal").modal("hide");
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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

	$("#btnUpdate").click(function(){
		$("#question-update-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnYesUpdate").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('update-role')}}",
			data: { _id: $("#txtSlctdRowID").val(), _name: $("#txtDisplayName").val(), _description: $("#txtDescription").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-update-modal").modal("hide");
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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

	$("#btnAddRole").click(function(){
		$("#auRoleModalTitle").text("Add Role");
		$("#txtSlctdRowID").val("");
		$("#txtDisplayName").val("");
		$("#txtDisplayName").removeAttr("disabled");
		$("#txtDescription").val("");
		$("#btnDelete").addClass("hideObj");
		$("#btnUpdate").addClass("hideObj");
		$("#btnSave").removeClass("hideObj");

		$("#au-role-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnSave").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('add-role')}}",
			data: { _name: $("#txtDisplayName").val(), _description: $("#txtDescription").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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

	//==============================================
	$("#btnDeleteUser").click(function(){
		$("#question-user-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});
	$("#btnYesDeleteUser").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('delete-user')}}",
			data: { _id: $("#txtSlctdRowID").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-user-modal").modal("hide");
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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
	$("#btnUpdateUser").click(function(){
		$("#question-user-update-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});
	$("#btnYesUpdateUser").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('update-user')}}",
			data: { _id: $("#txtSlctdRowID").val(), _name: $("#txtUser").val(), _email: $("#txtEmail").val(), _role: $("#slctRoleOption").find(":selected").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-user-update-modal").modal("hide");
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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
	$("#btnAddUser").click(function(){
		$("#auUserModalTitle").text("Add User");
		$("#txtSlctdRowID").val("");
		$("#txtUser").val("");
		$("#txtEmail").val("");
		$("#btnDeleteUser").addClass("hideObj");
		$("#btnUpdateUser").addClass("hideObj");
		$("#btnSaveUser").removeClass("hideObj");

		$("#au-user-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});
	$("#btnSaveUser").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('add-user')}}",
			data: { _name: $("#txtUser").val(), _email: $("#txtEmail").val(), _role: $("#slctRoleOption").find(":selected").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				if(msg.status_code == 0){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html(msg.msg);
				}
				else if(msg.status_code == -1){
					$("#msg-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
					$("#msgModalHeader").addClass("error");
					$("#msgModalTitle").empty().html("Opppss!");
					$("#msgModalBody").addClass("error").empty().html("There was an error. Please check the following:<br><br><i class='fas fa-times' style='padding-right: 10px;'></i>" + msg.msg.join("<br><i class='fas fa-times' style='padding-right: 10px;'></i>"));
				}
				else if(msg.status_code == 1){
					location.reload();
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
@endsection