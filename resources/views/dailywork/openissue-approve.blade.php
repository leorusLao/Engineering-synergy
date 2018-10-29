@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
	$(document).on("click","button.am-text-danger",function(){setTimeout('$("body").css("padding-right","0px")',0);});
</script>
@stop
 
@section('content')
<div class="col-xs-12">

<div class="col-xs-12 col-sm-12">


<div class="container-fluid">
	<div class="row-fluid">
		<div class="table-responsive table-scrollable">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
					    <th nowrap='nowrap'>{{Lang::get('mowork.openissue_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.openissue_resource')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.category')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.title')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.solution')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_department')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_peoper')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.approval_status')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_people')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.planned_completion_time')}}</th>
				        <th nowrap="nowrap">{{Lang::get('mowork.approval')}}</th>
					</tr>
				</thead>
				<tbody>

				<?php 
					foreach ($result as $key => $value) {
				?>
					<tr>
						<td style="display:none;">{{$value['detail_id']}}</td>
						<td><a href="/dashboard/openissue-edit/{{hash('sha256',$salt.$value['source_id'].$value['issue_id'])}}/{{$value['source_id']}}/{{$value['issue_id']}}">{{$value['detail_id']}}</a></td>						
						<td>{{$value['name']}}</td>
						<td>{{$value['proj_code']}}</td>
						<td>{{$value['plan_code']}}</td>
						<td>{{$value['plan_name']}}</td>
						<td>{{$value['class_name']}}</td>
						<td>{{$value['title']}}</td>
						<td>{{$value['solution']}}</td>
						<td>{{$value['dep_name']}}</td>
						<td>{{$value['str_leader']}}</td>
						<td>{{Lang::get('mowork.pending')}}</td>
						<td>{{$value['str_issuer']}}</td>
						<td>{{substr($value['issue_date'],0,10)}}</td>
						<td>{{$value['plan_complete_date']}}</td>
						<td nowrap="nowrap"> 
							@if($value['is_approved'] == 0)
	            			<a href="/dashboard/openissue-approval/stamp/{{hash('sha256',$salt.$value['detail_id'])}}/{{$value['detail_id']}}" target="_blank"><span><img src="/asset/img/stamp.png"></span></a>
							@endif
							</td>
					</tr>
				<?php }  ?>

				</tbody>
			</table>

		</div>
		<div class='text-center'><?php echo $result->links(); ?></div>
	</div>
</div>

</div>

</div>
@stop

@section('footer.append')

@stop