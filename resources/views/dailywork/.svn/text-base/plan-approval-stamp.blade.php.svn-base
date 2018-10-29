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
		    
		    <div class="table-responsive table-scrollable">
		    <table class="table data-table table-bordered" id="tb1">
         <tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}: {{$binfo->proj_code}}</td>
				<td>{{ Lang::get('mowork.project_name') }}: {{$binfo->proj_name}}</td> 
				<td>{{ Lang::get('mowork.project_type') }}: 
				<select disabled>
				 @foreach($projtypes as $pjt)
				 <option @if($pjt->type_id = $binfo->type) selected @endif>{{$pjt->name}}</option>
				 @endforeach
				</select></td> 
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.project_manager') }}: {{$binfo->proj_manager}}</td> 
				<td>{{ Lang::get('mowork.customer_number') }}: {{$binfo->customer_id}}</td>
				<td>{{ Lang::get('mowork.customer_name') }}: {{$binfo->customer_name}}</td> 
		 	</tr>
           <tr>
				<td>{{ Lang::get('mowork.plan_type') }}: 
				<select disabled>
				 @foreach($plantypes as $plt)
				 <option @if($plt->type_id = $binfo->plan_type) selected @endif>{{$plt->type_name}}</option>
				 @endforeach
				</select></td> </td> 
				<td>{{ Lang::get('mowork.plan_code') }}: {{$binfo->plan_code}}</td>
				<td>{{ Lang::get('mowork.plan_name') }}: {{$binfo->plan_name}}</td> 
		 	</tr>
		 	 
		</tbody>
		 

	       </table>
	<div>{{Lang::get('mowork.node_info')}}</div>
	 
		    
		    <form action='/dashboard/plan-approval/stamp/{{$token}}/{{$plan_id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
					 
						<th>{{Lang::get('mowork.node_code')}}</th>
						<th>{{Lang::get('mowork.node_name')}}</th>
						<th>{{Lang::get('mowork.department')}}</th>
						<th>{{Lang::get('mowork.duration')}}</th>
						<th>{{Lang::get('mowork.workdays')}}</th>
						<th>{{Lang::get('mowork.start_date')}}</th>
						<th>{{Lang::get('mowork.end_date')}}</th>
							 
					</tr>
				</thead>
				<tbody>
  
					@foreach ($rows as $row)
				 
					<tr>
						 
						<td>{{$row->node_no}}</td>
						<td><a href="/dashboard/plan-task-detail/view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">{{$row->name}}</a></td>
						<td><select disabled>
						    @foreach($departments as $dep)
						     <option value="{{$dep->dep_id}}" 
						         @if($dep->dep_id == $row->department) selected @endif>{{$dep->name}}</option> 
						    @endforeach
						    </select>
						</td>
						<td>{{$row->duration}}</td>
						<td>{{$row->workdays}}</td>
						<td>{{substr($row->start_date,0,10)}}</td>
						<td>{{substr($row->end_date,0,10)}}</td>
						 
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
			
			<div class='text-center'><?php echo $rows->links(); ?></div>
		</div>
	</div>
</div>
 
</div>

@stop
