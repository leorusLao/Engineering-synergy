@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')

@if(Session::has('result'))
	 <h4 class="text-center text-danger">
          {{Session::get('result')}}
     </h4>
@endif

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
<form action="/dashboard/change-password"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

@if($row->password)
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
</div>
<input class="form-control" placeholder="{{Lang::get('mowork.old_password')}}" name="oldPassword" type="password" value="" id="oldpassword" />
</div>
@endif
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
</div>
<input class="form-control" placeholder="{{Lang::get('mowork.password')}}" name="password" type="password" value="" id="password" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
</div>
<input class="form-control" placeholder="{{Lang::get('mowork.retype_password')}}" name="passwordConfirm" type="password" value="" id="password_confirm" />
</div>
 
 
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.change_password')}}">
</div>
 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
</div>
@stop 
@section('footer.append')
<script type="text/javascript">
$(function(){

    $('#me1').addClass('active');
    //$('#region').addClass('btn-info');

  /*
   offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2

    $('html,body').animate({
             scrollTop: offset > 0 ? offset:1000
    }, 200);
 */


});
 
function validateForm(){
    var errors = '';

    @if($row->password)
    var oldpassword = $.trim($('#oldpassword').val()); 
     
    if(oldpassword.length < 1) {
    	errors += "{{Lang::get('mowork.old_password_unmatch')}} \n";	
	}
    @endif
     
    var password = $.trim($('#password').val()); 
    if(password.length < 6) {
    	errors += "{{Lang::get('mowork.password_too_short')}} \n";	
	}
	
    var password2 = $.trim($('#password_confirm').val());
    if(password2 != password) {
     
		errors += "{{Lang::get('mowork.password_mismatch')}} \n";	
	}
    
	if(errors.length > 0) {
		alert(errors);
		return false;
	}
	return true;
    
  }
</script>
 
@stop