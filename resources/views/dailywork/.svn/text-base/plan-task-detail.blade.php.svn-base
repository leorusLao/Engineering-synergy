@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
 
    <div class="table-responsive table-scrollable">
	  
	<table class="table data-table table-bordered" id="tb1">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}: {{$row->proj_code}}</td>
				<td>{{ Lang::get('mowork.project_name') }}: {{$row->proj_name}}</td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.customer_number') }}: {{$row->customer_id}}</td>
				<td>{{ Lang::get('mowork.customer_name') }}: {{$row->customer_name}}</td> 
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.project_manager') }}: {{$row->proj_manager}}</td>
				<td>{{ Lang::get('mowork.approval_person') }}: {{$approver->fullname}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.plan_code') }}: {{$row->plan_code}}</td>
				<td>{{ Lang::get('mowork.plan_name') }}: {{$row->plan_name}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.approval_date') }}: {{$row->approval_date}}</td> 
				<td>{{ Lang::get('mowork.approval_status') }}: {{$row->status? Lang::get('mowork.agree'): Lang::get('mowork.disagree')}}</td> 
		 	</tr> 
		</tbody>

	</table>
	 
	 
    <table class="table data-table table-bordered" id="tb2">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.node_code') }}: {{$row->node_no}}</td>
				<td>{{ Lang::get('mowork.node_name') }}: {{$row->name}}</td> 
				<td>{{ Lang::get('mowork.responsible_peoper') }}: {{isset($team->fullname)?$team->fullname:''}}</td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.task_status') }}: @if($row->status == 0) {{Lang::get('mowork.pending')}}
				     @elseif ($row->status == 1) {{Lang::get('mowork.agree')}}
				     @elseif ($row->status == 2) {{Lang::get('mowork.disagree')}}
				     @endif
				</td>
				<td>{{ Lang::get('mowork.plan_start') }}: {{substr($row->start_date,0,10)}}</td>
				<td>{{ Lang::get('mowork.plan_completion') }}: {{substr($row->end_date,0,10)}}</td> 
		 	</tr>
           <tr>
				<td>{{ Lang::get('mowork.progress_quote') }}(%): {{$row->complete?$row->complete:''}}</td>
				<td>{{ Lang::get('mowork.real_start') }}: {{$row->real_start}}</td> 
				<td>{{ Lang::get('mowork.real_completion') }}: {{$row->real_end}}</td> 
		   </tr>
		 	  
		</tbody>

	</table>
</div>
<div class="margin-t20 text-center" onclick="window.close()" style="cursor: pointer">{{Lang::get('mowork.close')}}</div>
</div>
@stop 
  
@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    
     });
   
</script>


@stop