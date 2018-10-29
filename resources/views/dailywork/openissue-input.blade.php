@extends('backend-base') 

@section('css.append')
@stop
 
@section('content')
<div class="col-xs-12">

<div class="col-xs-12 col-sm-12">

<div class="panel-body">
   @foreach($sources as $row)
     <a href="/dashboard/openissue-input/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" class="btn btn-responsive button-alignment btn-default" style="margin-bottom:7px;" >
     {{$row->name}}
     </a>
   @endforeach
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		<div class="table-responsive table-scrollable">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<!-- <th style="text-align: center;"><input type="checkbox" style="zoom:130%;" /></th> -->
						<th nowrap='nowrap'>{{Lang::get('mowork.openissue_resource')}}</th>
						<?php if($result['resource']=='Plan' || $result['resource']=='Project'){ ?> 
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_category')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.manager')}}</th>
						<?php } ?>
						<?php if($result['resource']=='Plan'){ ?>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_type')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_name')}}</th>
						<?php } ?>
					 	<th nowrap='nowrap'>{{Lang::get('mowork.status')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.measurement_operation')}}</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if(!empty($result['cont'])){
					foreach ($result['cont'] as $key => $value) {
				?>
					<tr>
						<td><?php if(!empty($value['name'])){ echo $value['name'];} if($result['resource']=='Plan'){ ?> {{Lang::get('mowork.plan')}} <?php }else if($result['resource']=='Project'){ ?> {{Lang::get('mowork.project')}} <?php } ?></td>
						<?php if($result['resource']=='Plan' || $result['resource']=='Project'){ ?>
						<td>{{$value['proj_code']}}</td>
						<td>{{$value['proj_name']}}</td>
						<td><?php if($value['proj_type']==1){ echo 'Auto';}else if($value['proj_type']==2){ echo '3C';}else if($value['proj_type']==3){ echo 'Media';}  ?></td>
						<td>{{$value['proj_manager']}}</td>
						<?php } ?>
						<?php if($result['resource']=='Plan'){ ?>
						<td>{{$value['plan_type']}}</td>
						<td>{{$value['plan_code']}}</td>
						<td>{{$value['plan_name']}}</td>
						<?php } ?>
						<td><?php if($value['has_openissue']==0){ ?> {{Lang::get('mowork.unregistered')}} <?php }else if($value['has_openissue']==1){ ?> {{Lang::get('mowork.registered')}} <?php }   ?></td>
						<td><nobr>
						 <?php if(!empty($next_issue_id)) $value['issue_id'] = $next_issue_id; ?>
						<a href="/dashboard/openissue-edit/{{hash('sha256',$salt.$result['sourceid'].$value['issue_id'])}}/{{$result['sourceid']}}/<?php if(!empty($value['issue_id'])){echo $value['issue_id'];}else{ echo 0;} ?>" class="am-btn am-text-secondary"><i class="livicon" data-name="edit" data-size="12" data-c="#3bb4f2" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.text_edit')}}</a>
						<button type="button" class="am-btn am-text-danger" onclick="delete_project({{$value['project_id']}})"><i class="livicon" data-name="remove" data-size="12" data-c="#dd514c" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.import')}}</button>
						</nobr>
						</td>
					</tr>
				<?php } } ?>

				</tbody>
			</table>
			<div class='text-center'><?php echo $result['cont']->appends(array('sourceid'=>$result['sourceid']))->links(); ?></div>
		</div>
		</div>
	</div>
</div>

</div>

</div>
@stop

@section('footer.append')

@stop