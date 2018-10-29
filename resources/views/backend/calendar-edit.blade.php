@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')

@if(Session::has('result'))
   <div class="alert alert-danger">
          {{Session::get('result')}}
     </div>
@endif
<div class="col-xs-12 col-sm-4 col-sm-offset-4">
	<form action="/dashboard/calendar/edit/{{$token}}/{{$id}}" method='post'
		autocomplete='off' role='form' onsubmit='return validateForm();'>

		<div class="control-group">
			<label class="control-label" for="inputEmail">{{Lang::get('mowork.cal_code')}}</label>
			<div class="controls">
				<input id="cal_code" name="cal_code" type="text" class="form-control"
					value="{{$row->cal_code}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">{{Lang::get('mowork.cal_name')}}</label>
			<div class="controls">
				<input id="cal_name" name="cal_name" type="text" class="form-control"
					value="{{$row->cal_name}}" />
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
		 
	  var shift_code = $.trim($('#cal_code').val()); 
	  if(shift_code.length < 1) {
	  	errors += "{{Lang::get('mowork.calcode_required')}} \n";	
		}

	  var shift_name = $.trim($('#cal_name').val()); 
	  if(shift_name < 1) {
	     errors += "{{Lang::get('mowork.calname_required')}} \n";	
		}
	  
	  if(errors.length > 0) {
		alert(errors);
		return false;
	  }
	  return true;
	  
}
</script>
@stop

