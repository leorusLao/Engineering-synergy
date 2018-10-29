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
	         
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			 
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
	        <th nowrap="nowrap">{{Lang::get('mowork.customer_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.part_name')}}</th>
				 
			<th nowrap="nowrap">{{Lang::get('mowork.date_acceptance')}}</th>
      
            <th nowrap="nowrap">{{Lang::get('mowork.project_file_maintenance')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_file_maintenance')}}</th>
			</tr>
			</thead>

			<tbody>
 			<?php $last_proj = 0; ?>
			@foreach($rows as $row)

				<tr>
				 
				@if($row->proj_id != $last_proj)
				
				<td><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->proj_id)}}/{{$row->proj_id}}" target="_blank">{{ $row->proj_code }}</a></td>
			 
				@else
				<td></td>
		 
				@endif
				<td  >{{ $row->customer_name }}</td>
				<td  >{{ $row->part_name }}</td>
				 
				<td >{{ $row->planing_date }}</td>
				
				@if($row->proj_id != $last_proj)
				<td ><a href="/dashboard/project/file-maintenance/{{hash('sha256',$salt.$row->proj_id)}}/{{$row->proj_id}}" target="_blank">  
				<i class="glyphicon glyphicon-wrench"></i> {{ Lang::get('mowork.maintenance') }}</a>
				</td>
				@else
				<td></td>
				@endif
				
				<td><a href="/dashboard/file-maintenance/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" target="_blank">
				<i class="glyphicon glyphicon-wrench"></i> {{ Lang::get('mowork.maintenance') }}</a>
				</td>
				 
	 		</tr>
             <?php $last_proj = $row->proj_id ?>
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