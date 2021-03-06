@extends('backend-base')
@section('css.append')
<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
@stop
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
		   <form method="post" action="/dashboard/workboard/openissue-progress-input/{{$token}}/{{$id}}"  >
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
	         
						<th nowrap='nowrap'>{{Lang::get('mowork.openissue_resource')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.category')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.title')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.solution')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_department')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_peoper')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_people')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.planned_completion_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.actual_completion_time')}}</th>
            
         	</tr>
			</thead>

			<tbody>
 			
			@foreach($rows as $row)
                <?php if ($row->source_id == 1) {
					    $res = App\Models\Project::where('proj_id',$row->issue_id)->first();             
                	  } else if ($row->source_id == 2) {
                	  	$res = App\Models\Plan::leftJoin('project','project.proj_id','=','plan.project_id')->where('plan_id',$row->issue_id)->first();
                	  } else {//
                	    $res = App\Models\IssueSource::where('id', $row->source_id)->first();
                	  }
                ?>
				<tr>
				<td>@if($row->source_id == 1) {{Lang::get('mowork.project')}}
				    @elseif($row->source_id == 2) {{Lang::get('mowork.plan')}}
				    @else {{isset($res->code)? $res->code: ''}}
				    @endif
				</td>
				<td>@if($row->source_id == 1)
				       {{$res->proj_code}}
				     @elseif ($row->source_id == 2)
				       {{$res->proj_code}}
				     @else
				       &nbsp;
				     @endif
				<td>{{ isset($res->proj_name)?$res->proj_name:'' }}</td>
				 
				<td>{{ isset($res->plan_code)?$res->plan_code:'' }}</td>
				<td>{{ isset($res->plan_name)?$res->plan_name:'' }}</td>
				 
				<td></td><!--source-class-->
			  
				<td>{{$row->title}}</td>
				<td>{{$row->solution}}</td>
				<td >
				    <select disabled>
				    @foreach($departments as $dep)
				    <option @if($dep->dep_id == $row->department) selected @endif >{{$dep->name}}</option>
				    @endforeach
				    </select>
				</td>
				<td nowrap="nowrap">  
				  <select disabled>
				    @foreach ($employees as $e)
				    <option @if($e->uid == $row->leader) selected @endif>{{$e->fullname}}</option>
				    @endforeach
				  </select>
				</td>
				<td nowrap="nowrap">  
				   
				    <?php $issuers = explode(',',$row->issuer); $list = "";?>
				    @foreach ($employees as $e)
				       @if( in_array($e->uid, $issuers))
				          {{ $e->fullname}}<br>
				       @endif
				    @endforeach
				    
				</td>
				<td>{{substr($row->issue_date,0,10)}}</td>
				<td nowrap="nowrap">{{ substr($row->plan_complete_date,0,10) }}</td>
				<td>
				    <div class='input-group date' id='datepicker{{$row->id}}'>
                          <input type="text" name="real_end{{$row->id}}" id="date{{$row->id}}"
                           class="form-control" style="width:110px" 
                           value="{{$row->real_complete_date}}" onchange="updateCompleteDate({{$row->id}})">
                          <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                         </div>
				    <script type="text/javascript">
           					 $(function () {
               				   $('#datepicker{{$row->id}}').datetimepicker({
               				     minView: 2,
               					 format: "yyyy-mm-dd",
               			       });
           					 });
           					  
        			</script>
                </td>
         	</tr>
              
	 		@endforeach
	 		</tbody>
        	</table>
	 	    
            </div>
           
            
			</div>
			<input type="hidden" name="task_id" id="task_id" value="">
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			</form>
           
	 <div class="text-center" onclick="window.close()"><p class="btn">{{Lang::get('mowork.close')}}</p></div>
	 	 
  @endif


</div>
 
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/bootstrap-datetimepicker.js"></script> 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function updateCompleteDate(id) {
    	 
    	 vdate= $('#date'+id).val();
    	  $.ajax({
	          	type: 'POST',
	          	url: "{{url('/dashboard/workboard/openissue-progress-input/post')}}",
	          	 
	          	 data: {
	                   id: id,
	                   end_date: vdate,
	                   _token: "{{ csrf_token() }}" 
	              },
	              success: function( data ) {
	              },
	              error: function(xhr, status, error) {
	                   alert(error);
	              },
	              dataType: 'html'
	      	}); 
    }
    
</script>


@stop