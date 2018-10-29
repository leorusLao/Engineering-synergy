@extends('backend-base') 

@section('css.append')

@stop

@section('content')
   
	<div class="col-xs-12 col-sm-4 col-sm-offset-4">
			<h4 class="text-center margin-b30">{{Lang::get('mowork.worksite_entry_note')}}: {{$company_name}}</h4>
	@if(Session::has('result'))
	 <h4 class="text-center text-danger">
          {{Session::get('result')}}
     </h4>
    @endif
    <div class="text-center margin-b20"></div>
	<form action="/dashboard/enter-worksite/{{$token}}/{{$company_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
	<div class="form-group">
       <div class="text-center">
       <input class="form-control" placeholder="{{Lang::get('mowork.password')}}" name="password" type="password" value="" id="password" />
       </div>
    </div>
    
    <div class="form-group">
       <div><input type="submit" name ="submit" class="btn btn-md btn-info" value="{{Lang::get('mowork.enter')}}"></div>
    </div>
    <input name="_token" type="hidden" value="{{ csrf_token() }}">

    </form>
    
    </div>
@stop
@section('footer.append')

<script type="text/javascript">
$(function(){
    $('#me0').addClass('active');
});

function validateForm(){
    var errors = '';

    
    var password = $.trim($('#password').val()); 
    if(password.length < 1) {
    	errors += "{{Lang::get('mowork.password_required')}} \n";	
	}
	   
	if(errors.length > 0) {
		alert(errors);
		return false;
	}
	return true;
    
  }
</script>
 
@stop
