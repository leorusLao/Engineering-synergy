@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')

@if(Session::has('result'))
   <div class="text-center text-danger">
          {{Session::get('result')}}
     </div>
@endif
<div class="col-xs-12 col-sm-4 col-sm-offset-4">
	<form action="/dashboard/calendar/work-shift/edit/{{$token}}/{{$shift_id}}" method='post'
		autocomplete='off' role='form' onsubmit='return validateForm();'>

		<div class="control-group">
			<label class="control-label" for="inputEmail">{{Lang::get('mowork.shift_code')}}</label>
			<div class="controls">
				<input id="shift_code" name="shift_code" type="text" class="form-control"
					value="{{$row->shift_code}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">{{Lang::get('mowork.shift_name')}}</label>
			<div class="controls">
				<input id="shift_name" name="shift_name" type="text" class="form-control"
					value="{{$row->shift_name}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">{{Lang::get('mowork.worktime')}}</label>
			<div class="controls">
				<input id="worktime" name="worktime" type="text" class="form-control"
					value="{{$row->worktime}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">{{Lang::get('mowork.shift_color')}}</label>
			<div class="controls">
				<input id="shift_color" name="shift_color" type="text" class="form-control"
					value="{{$row->color}}" />
			</div>
		</div>
		<input type="hidden" name="_token" value="{{csrf_token()}}" />
		<div class="control-group margin-t20">
			<div class="controls">
				<input type="submit" name="submit" class="btn btn-info"	value="{{Lang::get('mowork.edit')}}">
			</div>
		</div>
	</form>
</div>

@stop 

@section('footer.append')
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
	  
	  if(errors.length > 0) {
		alert(errors);
		return false;
	  }
	  return true;
	  
}
</script>
@stop