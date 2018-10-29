@extends('backend-base')

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">

	<ul class="nav nav-justified margin-b30">
		<li><a href="/dashboard/calendar">{{Lang::get('mowork.calendar')}}{{Lang::get('mowork.name')}}</a></li>
		<li class='active'><a href="/dashboard/calendar/work-shift">{{Lang::get('mowork.work_shift')}}</a></li>
	</ul>

<div class="margin-b20">
	<a href='#formholder' onclick="addForm()" rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add')}}{{Lang::get('mowork.work_shift')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add')}}{{Lang::get('mowork.work_shift')}}</span></a>
</div>
 
@if(Session::has('result')) 
<h3 class="text-center text-danger marging-b20">{{Session::get('result')}}</h3>
@endif
 

@if(count($rows))
<div class="table-responsive table-scrollable">
<table class="table dataTable table-striped display table-bordered table-condensed">

	<thead>
		<tr>
			<th>{{Lang::get('mowork.shift_code')}}</th>
			<th class="sort-icon">{{Lang::get('mowork.shift_name')}}</th>
			<th>{{Lang::get('mowork.worktime')}}</th>
			<th>{{Lang::get('mowork.shift_color')}}</th>
			<th>{{Lang::get('mowork.maintenance')}}</th>
	 	</tr>
	</thead>

	<tbody>

		@endif 
		@foreach($rows as $row)

		<tr>

			<td>{{ $row->shift_code }}</td>
			<td>{{ $row->shift_name }}</td>
			<td>{{ $row->worktime }}</td>
			<td>{{ $row->color }}</td>
			<td><a href="/dashboard/calendar/work-shift/edit/{{hash('sha256',$salt.$row->shift_id)}}/{{$row->shift_id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			<a href="/dashboard/calendar/work-shift/delete/{{hash('sha256',$salt.$row->shift_id)}}/{{$row->shift_id}}"><span class="glyphicon glyphicon-trash"></span></a></td>
			
		 	</tr>

		@endforeach 
@if(count($rows))

	</tbody>

</table>
</div>

<div class='text-center'><?php echo $rows->links(); ?></div>

<div class="clearfix"></div>
 
@endif
</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.work_shift')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/calendar/work-shift/add' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group">
						<input type="text" class="form-control" name="shift_code"
							placeholder="{{Lang::get('mowork.shift_code')}}"
							title="{{Lang::get('mowork.shift_code')}}" id='shift_code'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="shift_name"
							placeholder="{{Lang::get('mowork.shift_name')}}"
							title="{{Lang::get('mowork.shift_name')}}" id='shift_name'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="worktime"
							placeholder="{{Lang::get('mowork.worktime')}}"
							title="{{Lang::get('mowork.worktime')}}" id='worktime'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="color"
							placeholder="{{Lang::get('mowork.shift_color')}}"
							title="{{Lang::get('mowork.shift_color')}}">
					</div>
					<div class="form-group">
						<input type="submit" class="form-control btn-info" name="submit"
							value="{{Lang::get('mowork.add')}}">
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
				</form>
			</div>
			<div class="modal-footer"></div>
			<div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div>
		</div>
	</div>
</div>

@stop

@section('footer.append')

<script type="text/javascript"
	src="/asset/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript"
	src="/asset/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

   
    $("[rel=tooltip]").tooltip({animation:false});
  
</script>
<script type="text/javascript"
	src="/asset/js/table-editable.js"></script>

<script type="text/javascript">

function validateForm(){
  var errors = '';
	 
  var shift_code = $.trim($('#shift_code').val()); 
  if(shift_code.length < 1) {
  	errors += "{{Lang::get('mowork.shiftcode_required')}} \n";	
	}

  var shift_name = $.trim($('#shift_name').val()); 
  if(shift_name < 1) {
     errors += "{{Lang::get('mowork.shiftname_required')}} \n";	
	}
  
  var worktime = $.trim($('#worktime').val());

  if(!$.isNumeric(worktime)){
	  errors += "{{Lang::get('mowork.worktime_required')}} \n";	
  }
  
   
	 
  if(errors.length > 0) {
	alert(errors);
	return false;
  }
  return true;
  
}
</script>

@stop