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
			<div class="text-center text-danger margin-b10">{{Lang::get('mowork.handin_for_approval')}}</div>
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.customer_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.customer_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_type')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.date_acceptance')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_type')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_number')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.status')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.handin')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.approval')}}</th>
         	</tr>
			</thead>

			<tbody>
 			
			@foreach($rows as $row)

				<tr>
				<td  ><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->proj_id)}}/{{$row->proj_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td  >{{ $row->customer_id }}</td>
				<td  >{{ $row->customer_name }}</td>
				<td  >{{ $row->proj_name }}</td>
				<td  >{{ $row->proj_type }}</td>
				<td >{{ $row->start_date }}</td>
			 
				<td >{{ $row->plan_type }}</td>
				<td >{{ $row->plan_code }}</td>
				<td ><a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" target="_blank">{{ $row->plan_name }}</a></td>
				<td >{{ $row->proj_manager }}</td>
				<td ><?php   
				        if($row->status == 2) echo Lang::get('mowork.not_handin');
				        elseif($row->status == 3) echo Lang::get('mowork.pending'); 
				        elseif($row->status == 5) echo Lang::get('mowork.disapproved');
				         
				     ?>
			    </td>
				<td nowrap="nowrap"> 
				@if($row->status == 2)
	            <a href="/dashboard/plan-approval/handin/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}">{{Lang::get('mowork.handin')}}</a>
				@endif
				</td>
				<td nowrap="nowrap"> 
				@if($row->status == 3)
	            <a href="/dashboard/plan-approval/stamp/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" target="_blank"><span><img src="/asset/img/stamp.png"></span></a>
				@endif
				</td>
 	 		</tr>

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