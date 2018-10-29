@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
 
@stop
 
@section('content')
<div class="col-xs-12 col-sm-12">
    @if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	<script type="text/javascript">
	 window.opener.location.reload();
	 window.close();
    </script>
	@endif 
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		    <div>{{ Lang::get('mowork.openissue_resource') }}: {{$isource->code}}</div> 
		    <div class="table-responsive table-scrollable">
		    
		    <form action='/dashboard/openissue-approval/stamp/{{$token}}/{{$issue->id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
					 
						<th>{{Lang::get('mowork.title')}}</th>
						<th>{{Lang::get('mowork.issue_class')}}</th>
						<th>{{Lang::get('mowork.description')}}</th>
						<th>{{Lang::get('mowork.solution')}}</th>
						<th>{{Lang::get('mowork.responsible_department')}}</th>
						<th>{{Lang::get('mowork.leader')}}</th>
						<th>{{Lang::get('mowork.planned_completion_time')}}</th>
						<th>{{Lang::get('mowork.put_forward_people')}}</th>
						<th>{{Lang::get('mowork.put_forward_time')}}</th>	
					</tr>
				</thead>
				<tbody>
  
					@foreach ($rows as $row)
				 
					<tr>
						 
						<td>{{$row->title}}</td>
						<td>{{$row->name}}</td>
						<td>{{$row->description}}</td>
						<td>{{$row->solution}}</td>
						<td>{{$row->department}}</td>
						<td>{{$row->leader}}</td>
						<td>{{$row->plan_complete_date}}</td>
						<td>{{$row->issuer}}</td> 
						<td>{{substr($row->issue_date,0,10)}}</td> 
					</tr>
				    @endforeach
				</tbody>
			</table>
			</div>
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			 
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group input-group">
              <div class="input-group-addon">{{Lang::get('mowork.approval_comment')}}</div>
               <textarea class="form-control" name="comment" id="biz_des" rows="2"></textarea>
            </div>
			 
			<div class="clearfix"></div>
			<div class="text-center"><input type="submit" class="btn btn-info" name="submit" value="{{Lang::get('mowork.agree')}}">
			 <input type="submit" class="btn btn-info" name="submit" value="{{Lang::get('mowork.disagree')}}"> <p class="btn btn-info" onclick="window.top.close()">{{Lang::get('mowork.cancel')}}</p></div>
			</form>
			</div>
		 	 
		</div>
	</div>
</div>
 
</div>

@stop

