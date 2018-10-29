@extends('backend-base') 

@section('css.append')
@stop
 
@section('content')
<div class="col-xs-12">

<div class="col-xs-12 col-sm-12">


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		@if(Session::has('result'))
		<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
		@endif 
		<div class="table-responsive table-scrollable">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<!-- <th style="text-align: center;"><input type="checkbox" style="zoom:130%;" /></th> -->
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.customer_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.customer_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_manager')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.date_acceptance')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_calendar')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.measurement_updatetime')}}</th>
						<!-- <th>{{Lang::get('mowork.entered')}}</th> -->
						<th nowrap='nowrap'>{{Lang::get('mowork.project_approval')}}</th>
						<th>{{Lang::get('mowork.measurement_operation')}}</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach ($result as $key => $value) {
				?>
					<tr>
						<!-- <td style="text-align: center;"><input type="checkbox" class="input_check" projectid="{{$value['proj_id']}}"/></td> -->
						<td style="display:none;">{{$value['proj_id']}}</td>
						<td><a href="/dashboard/show-project/{{hash('sha256',$salt.$value['proj_id'])}}/{{$value['proj_id']}}">{{$value['proj_code']}}</a></td>
						<td>{{$value['customer_id']}}</td>
						<td>{{$value['customer_name']}}</td>
						<td>{{$value['proj_name']}}</td>
						<td>{{$value['proj_manager']}}</td>
						<td>{{$value['start_date']}}</td>
						<td><a href="/dashboard/calendar/make/{{hash('sha256',$salt.$value['cal_id'])}}/{{$value['cal_id']}}">{{$value['cal_name']}}{{$value['cal_id']}}</a></td>
						<td>{{$value['updated_at']}}(UTC)</td>
						<!-- <td>1</td> -->
						<td>@if($value['approval_status'] == 1){{Lang::get('mowork.not_handin')}} @elseif($value['approval_status'] == 2){{Lang::get('mowork.pending')}} @endif</td>
						<td>
							@if($value['approval_status'] == 1)
							<a href="javascript:void(0);" class="handin" data-proj_id="{{$value['proj_id']}}"><i class="livicon" data-name="check" data-size="12" data-c="#000" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.handin')}}</a>
							@elseif($value['approval_status'] == 2)
							<a class="am-btn agree-comment" href="#formholder" rel="tooltip" data-toggle="modal" data-proj_id="{{$value['proj_id']}}"><i class="livicon" data-name="check" data-size="12" data-c="#000" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.agree')}}</a>
							<a class="am-btn am-text-danger disagree-comment" data-proj_id="{{$value['proj_id']}}" href="#formholder" rel="tooltip" data-toggle="modal"><i class="livicon" data-name="remove" data-size="12" data-c="#dd514c" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.reject')}}</a>
							
							@endif
						</td>
					</tr>
				<?php }  ?>

				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>


	<!-- <div class="form-group input-group col-sm-12 col-fl-add">
		<div class="form-group text-center fl">
		<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.approval')}}">
		</div>
	</div> -->


</div>

</div>
@stop

@section('footer.append')

<div class="modal fade" id="formholder">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.agree').Lang::get('mowork.approval_comment')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/approve-project' method='post' autocomplete='off' role=form> 	 
					<textarea name="approval_comment" id="approval_comment" style="width: 100%;margin-bottom: 20px;margin-top:5px;" rows="10"></textarea>
					<input type="hidden" name="approval_status" value="0" />
					<input type="hidden" name="proj_id" value="0" />
			    	<input type="submit" class="btn-info btn-sm" name="submit" id="submit" style="" value="{{Lang::get('mowork.confirm')}}">
					{{ csrf_field() }}
				</form>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(function(){
		// 递交
		$('.handin').click(function(){
			$('input[name="proj_id"]').val($(this).data('proj_id'));
			$('input[name="approval_status"]').val(2);
			$('#submit').click();
		});
		// 同意
		$('.agree-comment').click(function(){
			$('#approval_comment').val('');
			$('input[name="proj_id"]').val($(this).data('proj_id'));
			$('input[name="approval_status"]').val(3);
			$('.modal-title').text("{{Lang::get('mowork.agree').Lang::get('mowork.approval_comment')}}");
		});

		// 拒绝
		$('.disagree-comment').click(function(){ 
			$('#approval_comment').val('');
			$('input[name="proj_id"]').val($(this).data('proj_id'));
			$('input[name="approval_status"]').val(4);
			$('.modal-title').text("{{Lang::get('mowork.reject').Lang::get('mowork.approval_comment')}}");
		});
	});

</script>

@stop