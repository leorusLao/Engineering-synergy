@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-12">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
   
    <div class="col-sm-6" id="a1"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.plan_confirm')}} ({{$numTasks}}) </th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($confirmTasks as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/plan-task-confirm/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
			 
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
             
		</tbody>

	</table>
  </div>
  <div class="text-right margin-t-20" style="margin-right:40px">
  @if(count($confirmTasks) > 5) 
    <a href="/dashboard/workboard/plan-task-confirm/list">{{Lang::get('mowork.more')}}</a> 
  @endif
  </div> 
  </div>

 <div class="col-sm-6" id="a2"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.plan_list')}} ({{$numAllTasks}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($allTasks as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/plan-task-view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
		</tbody>

	</table>
  </div>	 
  <div class="text-right margin-t-20" style="margin-right:40px"> <a href="/dashboard/workboard/plan-task-list">{{Lang::get('mowork.more')}}</a></div>   
 </div>
  
 <div class="col-sm-6 margin-t20" id="a3"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.progress_input')}} ({{$numProgressTasks}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($progressTasks as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/task-progress-input/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
				 
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
             
		</tbody>

	</table>
  </div>
  <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5) <a href="/dashboard/workboard/task-progress-input/list">{{Lang::get('mowork.more')}}</a> @endif</div> 
  </div>
  
  
  
 <div class="col-sm-6 margin-t20" id="a4"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.delayed_plan')}} ({{$numDelayedTasks}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($delayedTasks as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/plan-task-view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
             
		</tbody>

	</table>
  </div>
  <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5)  <a href="/dashboard/workboard/plan-task-delayed/list">{{Lang::get('mowork.more')}}</a> @endif</div> 
  </div>

 <div class="col-sm-6 margin-t20" id="a5"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.department_plan')}} ({{$numDepTasks}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($depTasks as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/plan-task-view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
		</tbody>

	</table>
  </div>	 
  <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5)  <a href="/dashboard/workboard/my-department-task/list">{{Lang::get('mowork.more')}}</a> @endif
  </div> 
 </div>  
  
  <div class="col-sm-6 margin-t20" id="a6"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.delayed_openissue')}} ({{$numDelayedIssues}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($delayedIssues as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/openissue-view/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" target="_blank">{{$row->title}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
             
		</tbody>

	</table>
  </div>
 <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5) <a href="/dashboard/workboard/openissue-delayed/list">{{Lang::get('mowork.more')}}</a> @endif</div> 
  </div>
  
  <div class="col-sm-6 margin-t20" id="a7"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.openissue')}} {{Lang::get('mowork.progress_input')}} ({{$numProgressIssues}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($progressIssues  as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/openissue-progress-input/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" target="_blank">{{$row->title}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
             
		</tbody>

	</table>
  </div>
   <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5) <a href="/dashboard/workboard/openissue-progress-list">{{Lang::get('mowork.more')}}</a> @endif</div>
  </div>
  

 <div class="col-sm-6 margin-t20" id="a8"> 
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
        <thead><tr><th>{{Lang::get('mowork.openissue_list')}} ({{$numIssues}})</th></tr></thead>  
		<tbody>
		    <?php $lines = 0; ?>
		    @foreach($issues as $row)
		    <tr>
				<td><div class="col-sm-9"><a href="/dashboard/workboard/openissue-view/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" target="_blank">{{$row->title}}</a></div><div class="col-sm-3">{{substr($row->updated_at,0,10)}}</div></td>
		 	</tr>
		 	 <?php $lines++; if($lines == 5) break; ?>
		 	@endforeach
		 	@for($ii = 0; $ii < 5 - $lines; $ii++)
            <tr>
				<td>&nbsp;</td>
		 	</tr>
		 	@endfor
		</tbody>

	</table>
  </div>	 
  <div class="text-right margin-t-20" style="margin-right:40px">@if(count($confirmTasks) > 5) <a href="/dashboard/workboard/openissue-list">{{Lang::get('mowork.more')}}</a> @endif</div> 
 </div>  
 
  
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