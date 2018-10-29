@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
    <div>{{Lang::get('mowork.basic_info')}}</div>
    <div class="table-responsive table-scrollable">
	<form action="/dashboard/workboard/task-progress-input/{{$token}}/{{$row->task_id}}" method="post" onsubmit="return validateForm()">
	<table class="table data-table table-bordered" id="tb1">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}: {{$row->proj_code}}</td>
				<td>{{ Lang::get('mowork.project_name') }}: {{$row->proj_name}}</td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.customer_number') }}: {{$row->customer_id}}</td>
				<td>{{ Lang::get('mowork.customer_name') }}: {{$row->customer_name}}</td> 
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.project_manager') }}: {{$row->proj_manager}}</td>
				<td>{{ Lang::get('mowork.approval_person') }}: {{$approver->fullname}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.plan_code') }}: {{$row->plan_code}}</td>
				<td>{{ Lang::get('mowork.plan_name') }}: {{$row->plan_name}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.approval_date') }}: {{$row->approval_date}}</td> 
				<td>{{ Lang::get('mowork.approval_status') }}: {{$row->status? Lang::get('mowork.agree'): Lang::get('mowork.disagree')}}</td> 
		 	</tr> 
		</tbody>

	</table>
	
	<div>{{Lang::get('mowork.file_information')}}</div> 
    <table class="table data-table table-bordered" id="tb2">
        <thead>
                <th nowrap='nowrap'>{{Lang::get('mowork.filename')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.filesize')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.created_date')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.update_times')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.last_updated_time')}}</th>
        </thead>
		<tbody>
		    @foreach($docs as $row)
		    <tr>
		    	<td>{{$row->filename}}</td>
				<td>{{$row->fsize}}</td> 
				<td>{{substr($row->created_at,0,10)}}</td>
				<td>{{$row->version}}</td>
				<td>{{$row->updated_at}}</td>
			</tr>
		 	@endforeach
        </tbody>
	</table>
	<div>{{Lang::get('mowork.node_info')}}</div> 
    <table class="table data-table table-bordered" id="tb2">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.node_code') }}: {{$row->node_no}}</td>
				<td>{{ Lang::get('mowork.node_name') }}: {{$row->name}}</td> 
				<td>{{ Lang::get('mowork.responsible_peoper') }}: {{isset($team->fullname)?$team->fullname:''}}</td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.task_status') }}: @if($row->status == 0) {{Lang::get('mowork.pending')}}
				     @elseif ($row->status == 1) {{Lang::get('mowork.agree')}}
				     @elseif ($row->status == 2) {{Lang::get('mowork.disagree')}}
				     @endif
				</td>
				<td>{{ Lang::get('mowork.plan_start') }}: {{substr($row->start_date,0,10)}}</td>
				<td>{{ Lang::get('mowork.plan_completion') }}: {{substr($row->end_date,0,10)}}</td> 
		 	</tr>
           <tr>
				<td>{{ Lang::get('mowork.progress_quote') }}(%): <input type="text" name="progress" id="progress" value="{{$row->complete?$row->complete:''}}"></td>
				<td>{{ Lang::get('mowork.real_start').Lang::get('mowork.date') }}: <input type="text" name="real_start" id="real_start" value="{{$row->real_start}}"></td> 
				<td>{{ Lang::get('mowork.real_completion').Lang::get('mowork.date') }}: <input type="text" name="real_end" value="{{$row->real_end}}"></td> 
		   </tr>
		   <tr><td>{{ Lang::get('mowork.duration') }}: {{$row->duration - ($row->dayoffs?$row->dayoffs : 0)}} {{Lang::get('mowork.days')}}</td>
		   <td colspan="2"><label style="vertical-align: top">{{Lang::get('mowork.progress_remark')}}：</label> <textarea name="progress_remark"  rows="2" cols="60">{{$row->progress_remark}}</textarea></td></tr>
		 	  
		</tbody>

	</table>
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<div class="text-center"><input class="btn btn-info" type="submit" name="submit" value="{{Lang::get('mowork.confirm')}}">
	</div>
  </form>
</div>
<div class="margin-t20 text-center" onclick="window.close()" style="cursor: pointer">{{Lang::get('mowork.close')}}</div>
</div>
@stop 
  
@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    
     });


 

function validateForm() {
	  percent = $('#progress').val();
	  real_start = $('#real_start').val();
      errors = '';
	   
	  if( ! $.isNumeric(percent) ) {
   		     errors += "{{Lang::get('mowork.progress_required')}}\n";
   	  }
   	  
   	  if(! isValidDate(real_start)) {
    	errors += "{{Lang::get('mowork.realstart_required')}}\n";
      }

      if(errors) {
		alert(errors);
		return false;
      }

      return true;
 }

function isValidDate(dateString)
{
    	 
      // First check for the pattern
      var regex_date = /^\d{4}\-\d{1,2}\-\d{1,2}$/;

      if(!regex_date.test(dateString))
      {
          return false;
      }

      // Parse the date parts to integers
      var parts   = dateString.split("-");
      var day     = parseInt(parts[2], 10);
      var month   = parseInt(parts[1], 10);
      var year    = parseInt(parts[0], 10);

      // Check the ranges of month and year
      if(year < 1000 || year > 3000 || month == 0 || month > 12)
      {
          return false;
      }
   	  return true;
     }

 </script>
@stop
