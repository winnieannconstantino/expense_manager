@extends('layout.main_layout')

@section('contents')
<link rel="stylesheet" type="text/css" href="{{url('bootstrap/css/jquery.simple-dtpicker.css')}}">
<script type="text/javascript" src="{{url('bootstrap/js/jquery.simple-dtpicker.js')}}"></script>
<style type="text/css">
.form-control:disabled, .form-control[readonly] {
    background-color: inherit; 
}
</style>
<div style="padding: 0px 50px;">
	@if($_itemRequest === "Categories")
		@if(Session::get("user_details")[0]["role"] === "Administrator")
		<div class="row">
			<div class="col-md-5">
				<h4>{!! $_itemRequest !!}</h4>
			</div>
			<div class="col-md-7" style="text-align: right;">
				<label>Expense Management&nbsp;&nbsp;>&nbsp;&nbsp;{!! $_itemRequest !!}</label>
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-12">
				<input type="hidden" id="txtSlctdRowID">
				<table class="table table-hover" id="tblDisplay">
					<thead>
						<tr>
							<th scope="col" style="display: none;"></th>
							<th scope="col">Display Name</th>
							<th scope="col">Description</th>
							<th scope="col">Created at</th>
						</tr>
					</thead>
					<tbody>
						{!! $_tableDetails !!}
					</tbody>
				</table>
				<br>
				<button type="button" class="btn btn-primary float-right" id="btnAddCategory">Add Category</button>
			</div>
		</div>
		@else
		<div class="row">
			<div class="col-md-12">
				<h2>You don't have access here.</h2>
			</div>
		</div>
		@endif
	@else
	<div class="row">
			<div class="col-md-5">
				<h4>{!! $_itemRequest !!}</h4>
			</div>
			<div class="col-md-7" style="text-align: right;">
				<label>Expense Management&nbsp;&nbsp;>&nbsp;&nbsp;{!! $_itemRequest !!}</label>
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-12">
				<input type="hidden" id="txtSlctdRowID">
				<table class="table table-hover" id="tblDisplay">
					<thead>
						<tr>
							<th scope="col" style="display: none;"></th>
							<th scope="col">Expense Category</th>
							<th scope="col">Amount</th>
							<th scope="col">Entry Date</th>
							<th scope="col">Created at</th>
						</tr>
					</thead>
					<tbody>
						{!! $_tableDetails !!}
					</tbody>
				</table>
				<br>
				<button type="button" class="btn btn-primary float-right" id="btnAddExpenses">Add Expense</button>
			</div>
		</div>
	@endif
</div>
@if($_itemRequest === 'Categories')
<div class="modal" tabindex="-1" role="dialog" id="au-categories-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="auCategoriesModalTitle"></h5>
			</div>
			<div class="modal-body" id="auCategoriesModalBody">
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
<div class="modal" tabindex="-1" role="dialog" id="question-category-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to delete this category?</label>
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
				<label>Are you sure you want to update this category?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesUpdate">Yes</button>
			</div>
		</div>
	</div>
</div>
@else
<div class="modal" tabindex="-1" role="dialog" id="au-expenses-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="auExpensesModalTitle"></h5>
			</div>
			<div class="modal-body" id="auExpensesModalBody">
				<form>
					<div class="form-group">
						<label for="slctExpensesOption">Expense Category</label>
						<select id="slctExpensesOption" class="form-control custom-select">
							{!! $_slctOption !!}
						</select>
					</div>
					<div class="form-group">
						<label for="txtAmount">Amount</label>
						<input type="text" class="form-control" id="txtAmount" required>
					</div>
					<div class="form-group">
						<label for="txtEntryDate">Entry Date</label>
						<input type="text" class="form-control" id="txtEntryDate" required readOnly>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger float-left" id="btnDeleteExpenses">Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-primary" id="btnUpdateExpenses">Update</button>
				<button type="button" class="btn btn-primary" id="btnSaveExpenses">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-expenses-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question</h5>
			</div>
			<div class="modal-body">
				<label>Are you sure you want to delete this expenses?</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
				<button type="button" class="btn btn-danger" id="btnYesDeleteExpenses">Yes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="question-expenses-update-modal">
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
				<button type="button" class="btn btn-danger" id="btnYesUpdateExpenses">Yes</button>
			</div>
		</div>
	</div>
</div>
@endif
<script type="text/javascript">
$(document).ready(function(){

	$("#txtEntryDate").appendDtpicker({
		"dateOnly":true,
		"locale":"en",
		"animation":true,
		"autodateOnStart":true
	});

	$("#tblDisplay tr").click(function(event){
		if("{{$_itemRequest}}" == "Categories"){
			$("#auCategoriesModalTitle").text("Update Categories");
			$("#txtSlctdRowID").val($(this).find('td:eq(0)').text());
			$("#txtDisplayName").val($(this).find('td:eq(1)').text());
			//$("#txtDisplayName").attr("disabled", "disabled");
			$("#txtDescription").val($(this).find('td:eq(2)').text());
			$("#btnSave").addClass("hideObj");
			$("#btnDelete").removeClass("hideObj");
			$("#btnUpdate").removeClass("hideObj");

			$("#au-categories-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
		}
		else{
			$("#auExpensesModalTitle").text("Update Expenses");
			$("#txtSlctdRowID").val($(this).find('td:eq(0)').text());
			//$("#slctExpensesOption").val($(this).find('td:eq(1)').text()).trigger('change');
			$("#slctExpensesOption option[text-value="+ $(this).find('td:eq(1)').text() +"]").prop("selected","selected");
			$("#txtAmount").val($(this).find('td:eq(2)').text());
			$("#txtEntryDate").val($(this).find('td:eq(3)').text());
			$("#btnSaveExpenses").addClass("hideObj");
			$("#btnDeleteExpenses").removeClass("hideObj");
			$("#btnUpdateExpenses").removeClass("hideObj");
			$("#au-expenses-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
		}
	});

	$("#btnDelete").click(function(){
		$("#question-category-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnYesDelete").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('delete-category')}}",
			data: { _id: $("#txtSlctdRowID").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-category-modal").modal("hide");
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
			url: "{{url('update-category')}}",
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

	$("#btnAddCategory").click(function(){
		$("#auCategoriesModalTitle").text("Add Category");
		$("#txtSlctdRowID").val("");
		$("#txtDisplayName").val("");
		$("#txtDisplayName").removeAttr("disabled");
		$("#txtDescription").val("");
		$("#btnDelete").addClass("hideObj");
		$("#btnUpdate").addClass("hideObj");
		$("#btnSave").removeClass("hideObj");

		$("#au-categories-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnSave").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('add-category')}}",
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
	$("#btnDeleteExpenses").click(function(){
		$("#question-expenses-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnYesDeleteExpenses").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('delete-expenses')}}",
			data: { _id: $("#txtSlctdRowID").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-expenses-modal").modal("hide");
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

	$("#btnUpdateExpenses").click(function(){
		$("#question-expenses-update-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnYesUpdateExpenses").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('update-expenses')}}",
			data: { _id: $("#txtSlctdRowID").val(), _category: $("#slctExpensesOption").find(":selected").val(), _amount: $("#txtAmount").val().replace("$",""), _entryDate : $("#txtEntryDate").val() },
			dataType: "JSON",
			headers: {'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")},
			success : function(msg){
				$("#question-expenses-update-modal").modal("hide");
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

	$("#btnAddExpenses").click(function(){
		$("#auExpensesModalTitle").text("Add Expenses");
		$("#txtSlctdRowID").val("");
		$("#txtDisplayName").val("");
		$("#txtDisplayName").removeAttr("disabled");
		$("#txtDescription").val("");
		$("#btnDeleteExpenses").addClass("hideObj");
		$("#btnUpdateExpenses").addClass("hideObj");
		$("#btnSaveExpenses").removeClass("hideObj");

		$("#au-expenses-modal").modal({keyboard: false, backdrop: 'static'}).modal("show");
	});

	$("#btnSaveExpenses").click(function(){
		$.ajax({
			type : "POST",
			url: "{{url('add-expenses')}}",
			data: { _category: $("#slctExpensesOption").find(":selected").val(), _amount: $("#txtAmount").val().replace("$","").trim(), _entryDate : $("#txtEntryDate").val() },
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