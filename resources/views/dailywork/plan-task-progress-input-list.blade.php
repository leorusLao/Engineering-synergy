@extends('backend-base')

@section('content')

<div class="col-xs-12">

@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	<script type="text/javascript">
	document.location.reload(true);
	</script>
	@endif
	@if(isset($errors))
		<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
		@endif
		@if(count($rows))
			 
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.task_status')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>

			<th nowrap="nowrap">{{Lang::get('mowork.plan_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.node_code')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.node_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.department')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_start')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_completion')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.real_start')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.real_completion')}}</th>

			</tr>
			</thead>

			<tbody>
				
			@foreach($rows as $row)

				<tr>
				<td nowrap="nowrap"> @if ($row->process_status == 0) <input type="checkbox" name="pstatus[]" id="ps{{$row->task_id}}" value="{{$row->task_id}}">{{Lang::get('mowork.unprocessed')}}
				@elseif ($row->process_status == 1) {{Lang::get('mowork.accepted')}}
				@elseif ($row->process_status == 2) {{Lang::get('mowork.unaccepted')}}
				@elseif ($row->process_status == 3) {{Lang::get('mowork.processing')}}
				@elseif ($row->process_status == 10) {{Lang::get('mowork.completed')}}
				@endif
				</td>
					
				<td><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->project_id)}}/{{$row->project_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td>{{ $row->proj_name }}</td>
					
				<td><a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" target="_blank">{{ $row->plan_code }}</a></td>
				<td>{{ $row->plan_name }}</td>
					
				<td >{{ $row->node_no  }}</td>

				<td >
				@if ($row->process_status == 0 || $row->process_status == 1 || $row->process_status == 3) 
				<a href="/dashboard/workboard/task-progress-input/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{ $row->name }}</a>
				@else
				<a href="/dashboard/workboard/plan-task-view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{ $row->name }}</a>
				@endif
				</td>
				<td >
				<select disabled>
				@foreach($departments as $dep)
					<option @if($dep->dep_id == $row->department) selected @endif >{{$dep->name}}</option>
				@endforeach
					</select>
					</td>
					<td >{{ substr($row->start_date,0,10) }}</td>
					<td nowrap="nowrap">{{ substr($row->end_date,0,10) }}</td>
					<td>{{ $row->real_start }}</td>
					<td>{{ $row->real_end }}</td>
						
					</tr>

			@endforeach

					</tbody>
					</table>

					</div>
	 
	 				<div class='text-center'><?php echo $rows->links(); ?></div>
  @endif


</div>
 
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm() {
    	var errors = '';
   	 
        $cbx = $("input:checkbox[name='pstatus[]']"); 
        if(! $cbx.is(":checked") ){
        	  errors = "{{Lang::get('mowork.check_node_required')}}\n";
        }  
      
        if(errors.length > 0) {
      	  alert(errors);
      	  return false;
        }
         
        return true;
    }
</script>


@stop
