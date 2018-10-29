@extends('front-base')

@section('css.append')
 <link media="all" type="text/css" rel="stylesheet" href="/asset/css/sp.css">
@stop

@section('main-body')
<div class="container" style="margin-top:30px;">
<div class="row margin-t50 margin-bottom-20">
<div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title text-center">{{Lang::get('mowork.lost_password')}}</h3>
</div>
  
<div class="panel-body" id='mainbody'>
 
@if(Session::has('result')) 
<h5 class="text-center text-danger marging-b20">{{Session::get('result')}}</h5>
@endif

<form action="/lost-password"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
</div>
<input class="form-control" placeholder="{{Lang::get('mowork.password_help')}}" name="username" type="text" value="" id="username" />
</div>
   
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info btn-block" value="{{Lang::get('mowork.request_password')}}">
</div>
 
<input name="_token" type="hidden" value="{{ csrf_token() }}">
 
<input name="client_type" value="1" type="hidden">
  
</fieldset>
</form>
</div>
</div>
</div>
</div>
</div>
@stop

@section('footer.append')
  
 
<script type='text/javascript'>

$(function() {
	(function(seconds) {
  	    var refresh,       
  	        intvrefresh = function() {
  	            clearInterval(refresh);
  	            refresh = setTimeout(function() {
  	               location.href = location.href;
  	            }, seconds * 1000);
  	        };

  	    $(document).on('keypress click', function() { intvrefresh() });
  	    intvrefresh();

  	 }(3600));
 
	 
});

 
 
  function validateForm(){
    var errors = '';
    username = $.trim($('#username').val());
    
    if(! validateEmail(username)  && ! validatePhone(username)){
 		errors += "{{Lang::get('mowork.invalid_email_mobile')}} \n";	
         alert(errors);
         return false;
    }
    
	return true;
    
  }

  function validatePhone(phone) {
	  
 		var stripped = phone.replace(/[\(\)\.\-\ ]/g, '');
    	var phoneRegex = /^\d{10,16}$/; // Change this rege based on requirement
   
    	return phoneRegex.test(stripped); 
  	 
  }
  
  function validateEmail(email) { 
 		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	    return re.test(email); 
  } 

 
  </script>
@stop