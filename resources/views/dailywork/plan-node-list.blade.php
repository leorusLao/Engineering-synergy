@extends('backend-base')

@section('content')

<div class="col-xs-12">

@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
	@if(isset($errors))
		<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
	@endif
	@if(count($rows))
	    {{ Form::open(array('url' => '/dashboard/plan-node-list', 'method' => 'post', 'class' => 'form-inline')) }}
       
  		   {{ Form::text('qtext','',array('class' => 'form-control', 'id' => 'qtext' )) }}
  	       {{ Form::submit(Lang::get('mowork.search'),array('name' => 'search','class' => 'btn btn-info','id' =>'sbmtbtn')) }}
        
        {{ Form::close()}}
			<div class="margin-t20 table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
		    <th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.customer_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_number')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.node_code')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.node_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.department')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.task_status')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.approval')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_start')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_completion')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.real_start')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.real_completion')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.complete')}}&#9679;{{Lang::get('mowork.anti_complete')}}</th>
         	</tr>
			</thead>

			<tbody>
 			<?php $last_plan_id = 0;?>
			@foreach($rows as $row)

				<tr>
				@if($row->plan_id == $last_plan_id)
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				@else
				<td  ><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->project_id)}}/{{$row->project_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td  >{{ $row->proj_name }}</td>
				<td  >{{ $row->customer_name }}</td>
				<td  ><a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" target="_blank">{{ $row->plan_code }}</a></td>
				<td  >{{ $row->plan_name }}</td>
				@endif
				<td >{{ $row->node_no  }}</td>
			 
				<td >{{ $row->name }}</td>
				<td >{{ $row->department }}</td>
				<td >{{ $row->process_status }}</td>
				<td ><a href="/dashboard/plan-task-detail/view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">
				      @if ($row->process_status == 0) {{Lang::get('mowork.unprocessed')}}
				      @elseif ($row->process_status == 1) {{Lang::get('mowork.accepted')}}
				      @elseif ($row->process_status == 2) {{Lang::get('mowork.unaccepted')}}
				      @elseif ($row->process_status == 3) {{Lang::get('mowork.processing')}}
				      @elseif ($row->process_status == 10) {{Lang::get('mowork.completed')}}
				      @endif
				       
				    </a>
				</td>
				<td ><?php  if($row->status == 0) echo Lang::get('mowork.pending');
				        elseif($row->status == 1) echo Lang::get('mowork.agree');
				        elseif($row->status == 2) echo Lang::get('mowork.disagree');
				     ?>
			    </td>
				<td >{{ substr($row->start_date,0,10) }}</td>
				<td nowrap="nowrap">{{ substr($row->end_date,0,10) }}</td>
				<td>{{ $row->real_start }}</td>
				<td>{{ $row->real_complete }}</td>
				<td class="text-center"> 
	            <a href="/dashboard/complete-plan-node/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" title="{{Lang::get('mowork.complete')}}"><span class="glyphicon glyphicon-off"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/dashboard/anti-complete-plan-node/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" title="{{Lang::get('mowork.anti_complete')}}" @if($row->process_status != 10) style="pointer-events: none;" @endif><span class="glyphicon glyphicon-repeat"></span></a>
				</td>
 	 		</tr>
             <?php $last_plan_id = $row->plan_id; ?>
	 		@endforeach

	 		</tbody>

	 		</table>

	 		<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>
 
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});
     
</script>


@stop