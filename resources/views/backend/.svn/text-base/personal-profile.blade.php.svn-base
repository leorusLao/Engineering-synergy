@extends('backend-base') 
 
@section('content')
<div class="col-xs-12 col-sm-4 col-sm-offset-4">

@if(Session::has('result'))
	 <div class="alert alert-warning">
          {{Session::get('result')}}
     </div>
@endif

<h4 class="text-danger">{{Lang::get('mowork.personal_update_warning')}}</h4>
<form action="/dashboard/personal-profile"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.fullname')}}</i>
</div>
<input class="form-control" name="fullname" type="text" value="{{$row->fullname}}" id="fullname" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.nickname')}}</i>
</div>
<input class="form-control" name="nickname" type="text" value="{{$row->username}}" id="nickname" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="cellphone" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.mobile')}}</i>
</div>
<input class="form-control" name="mobile" type="text" value="{{$row->mobile}}" id="mobile" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.email')}}</i>
</div>
@if($row->banded_email)
<input class="form-control" name="email" type="text" value="{{$row->email}}" id="email" readonly />
@else
<input class="form-control" name="email" type="text" value="{{$row->email}}" id="email" />
@endif
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.wechat')}}</i>
</div>
<input class="form-control" name="wechat" type="text" value="{{$row->wechat}}" id="mobile" readonly />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.country')}}</i>
</div>
{{ Form::select('country', $countryList,$row->country_id, array('class' => 'form-control','id' => 'country')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.province_name')}}</i>
</div>
{{ Form::select('province', $provinceList, $row->province_id, array('class' => 'form-control','id' => 'province')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.city')}}</i>
</div>
 
{{ Form::select('city', $cityList, $row->city_id, array('class' => 'form-control','id' => 'city')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="address-book" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.address')}}</i>
</div>
<input class="form-control" name="address" type="text" value="{{$row->address}}" id="address" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.postcode')}}</i>
</div>
<input class="form-control" name="postcode" type="text" value="{{$row->postcode}}" id="postcode" />
</div>
 
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
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

    $('#country').change(function() {
    	  
    	 $.ajax({
	        type:"GET",
	        url : '/dropdown',
	        data : { country: $(this).val() },
	        dataType: 'json',
	        success : function(result) {
	        	 
	        	var options = '';
	        	for(var ii in result){
	        		options += "<option value='" + ii + "'>" + result[ii] + "</option>";
	        	}
	        	 
	          $('#province').html(options);
	          $('#city').html('');
	        },
	        error: function() {
		         
	            //do logic for error
	        }
	    });
	    
	 });
	 
	 
	  $('#province').change(function() {
    	 $.ajax({
	        type:"GET",
	        url : '{{url("/dropdown")}}',
	        data : { province: $(this).val() },
	        dataType: 'json',
	        success : function(result) {
	        	
	        	var options = '';
	        	for(var ii in result){
	        		options += "<option value='" + ii + "'>" + result[ii] + "</option>";
	        	}
	        	 
	          $('#city').html(options);
	        },
	        error: function() {
	            //do logic for error
	        }
	    });
	    
	   }); 	    


});
 
function validateForm() {
    var errors = '';

    fullname = $.trim($('#fullname').val());  
    username = $.trim($('#nickname').val());
	 
	isPhone = false;

	if(fullname.length < 1) {
		errors += "{{Lang::get('mowork.fullname_required')}} \n";	
	}
	
	if(username.length < 1) {
		errors += "{{Lang::get('mowork.nickname_required')}} \n";	
	}

	email = $.trim($('#email').val());

	if(email.length > 0){
		if(! validateEmail(email) ){
			errors += "{{Lang::get('mowork.invalid_email')}} \n";	
		}
	}

    phone = $.trim($('#phone').val());

    if(phone.length > 0 ) {
    	if(! validatePhone(phone) ) {
    		errors += "{{Lang::get('mowork.invalid_phone')}} \n";	
        }
    }
  
	if(errors.length > 0) {
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