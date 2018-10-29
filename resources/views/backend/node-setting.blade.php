@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
   <form action='/dashboard/project-config/node-setting' method='post'
					autocomplete='off' role='form'>
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
 
		<tbody>
            <tr>
				<td>{{ Lang::get('mowork.node_s1') }}</td>
				<td><input type="checkbox" name="completion_date" value="{{$row->completion_date}}" @if($row->completion_date) checked @endif disabled class='form-control'></td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.node_s2') }}</td>
				<td><input type="checkbox" name="percent_done" value="{{ $row->percent_done }}" @if($row->percent_done) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s3') }}</td>
				<td><input type="checkbox" name="cover_children" value="{{ $row->cover_children }}" @if($row->cover_children) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s4') }}</td>
				<td><input type="checkbox" name="parent_auto" value="{{ $row->parent_auto }}" @if($row->parent_auto) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s5') }}</td>
				<td><input type="checkbox" name="task_advise_header" value="{{ $row->task_advise_header }}" @if($row->task_advise_header) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s6') }}</td>
				<td><input type="checkbox" name="task_advise_pmanager" value="{{ $row->task_advise_pmanager }}" @if($row->task_advise_pmanager) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s7') }}</td>
				<td><input type="checkbox" name="task_advise_pmember" value="{{ $row->task_advise_pmember }}" @if($row->task_advise_pmember) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s8') }}</td>
				<td><input type="checkbox" name="done_advise_supervisor" value="{{ $row->done_advise_supervisor }}" @if($row->done_advise_supervisor) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s9') }}</td>
				<td><input type="checkbox" name="done_advise_header" value="{{ $row->done_advise_header }}" @if($row->done_advise_header) checked @endif disabled class='form-control'></td>
		 	</tr>
		    <tr>
				<td>{{ Lang::get('mowork.node_s10') }}</td>
				<td><input type="checkbox" name="done_advise_pmanager" value="{{ $row->done_advise_pmanager }}" @if($row->done_advise_pmanager) checked @endif disabled class='form-control'></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_s11') }}</td>
				<td><input type="checkbox" name="done_advise_pmember" value="{{ $row->done_advise_pmember }}" @if($row->done_advise_pmember) checked @endif disabled class='form-control'></td>
		 	</tr>
		</tbody>

	</table>
	<input name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class="btn btn-info text-center" onclick="activeEdit()" id='edit' type="submit">{{Lang::get('mowork.edit')}}</div>
	<input class="btn btn-info text-center" name="submit" type="submit"  value="{{Lang::get('mowork.save')}}"  id="submit" style="display:none">
    </div>
    </form>
    
	<div class="clearfix"></div>

</div>

@stop 

@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	 
        $('#me8').addClass('active');   
        /*
         offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2
	      
          $('html,body').animate({
        	   scrollTop: offset > 0 ? offset:1000
          }, 200);
       */
             

     });
 

    function activeEdit() {
    	 $('.form-control').prop("disabled", false);
    	 $('#edit').css('display','none');
    	 $('#submit').css('display','block');
    	 
    }
</script>


@stop