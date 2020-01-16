@extends('layout.main_layout')

@section('contents')
<style type="text/css">
.pie{
	height: calc(var(--size, 200) * 1px);
	position: relative;
	width: calc(var(--size, 200) * 1px);
	background: #639;
	border-radius: 100%;
	
}
.pie__segment{
	height: 100%;
	position: absolute;
	transform: translate(0, -50%) rotate(90deg) rotate(0deg);
	transform-origin: 50% 100%;
	width: 100%;
	/*--a: calc(var(--over50, 0) * -100%);
	--b: calc((1 + var(--over50, 0)) * 100%);
	--degrees: calc((var(--offset, 0) / 100) * 360);
	transform: translate(0, -50%) rotate(90deg) rotate(calc(var(--degrees) * 1deg));
	clip-path: polygon(var(--a) var(--a), var(--b) var(--a), var(--b) var(--b), var(--a) var(--b)) !important;
	-webkit-clip-path: polygon(var(--a) var(--a), var(--b) var(--a), var(--b) var(--b), var(--a) var(--b)) !important;*/
}
.pie__segment:after,
.pie__segment:before{
	/*background: var(--bg);
	content: '';
	height: 100%;
	position: absolute;
	width: 100%;*/

}
.pie__segment:before{
	background: rgba(255,0,0,0.5);
	content: '';
	height: 100%;
	position: absolute;
	transform: translate(0, 50%) rotate(80deg);
	transform-origin: 50% 0%;
	width: 100%;
	/*--degrees: calc((var(--value, 45) / 100) * 360);
	background: var(--bg);
	transform: translate(0, 100%) rotate(calc(var(--degrees) * 1deg));*/
}
.pie__segment:after{
	/*opacity: var(--over50, 0);*/

}
</style>
<div style="padding: 0px 50px;">
	<div class="row">
		<div class="col-md-5">
			<h4>My Expenses</h4>
		</div>
		<div class="col-md-7" style="text-align: right;">
			<label>Dashboard</label>
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-md-5">
			<table class="table">
				<thead style="text-align: center;">
					<tr>
						<th scope="col">Expense Categories</th>
						<th scope="col">Total</th>
					</tr>
				</thead>
				<tbody>
					{!! $_trItems !!}
				</tbody>
			</table>
		</div>
		<div class="col-md-7" style="text-align: right;">
			<div class="piechart">
				
			</div>
		</div>
	</div>
</div>
@endsection