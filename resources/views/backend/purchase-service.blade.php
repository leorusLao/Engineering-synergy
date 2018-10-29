@extends('backend-base') 
 
@section('content')
<div class="col-xs-12 col-sm-6 col-sm-offset-3">

<h4 class="text-danger text-center">{{Lang::get('mowork.purchase_service')}}</h4>
@if(Session::has('result'))
	 <div class="alert alert-warning">
          {{Session::get('result')}}
     </div>
@endif

@if(count($permit) > 0)
 <h4 class="text-center">{{Lang::get('mowork.valid_user_permit')}}</h4>

  <div class="text-center">{{Lang::get('mowork.effect_date')}}: {{$permit->effect_date}} </div>
  <div class="text-center">{{Lang::get('mowork.expiration_date')}}: {{$permit->expiry_date}} </div>
  <div class="text-center">{{Lang::get('mowork.permit_number')}}: {{$permit->user_permits}} </div>
@else
 <h4 class="text-center">{{Lang::get('mowork.non_permit')}}</h4>
@endif

<form action="/dashboard/purchase-service"  class="form-horizontal" method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group">
 
<label class="col-sm-6 control-label">Package 1 - ￥5000： 5用户 </label>
<div class="col-sm-6"> 
<input class="form-control chb" name="package[]" type="checkbox" value="1" />
</div>
</div>
 
<div class="form-group">
 
<label class="col-sm-6 control-label">Package 2 - ￥8000： 10用户 </label>
 <div class="col-sm-6"> 
<input class="form-control chb" name="package[]" type="checkbox" value="2"  />
</div>
</div>
 
 
<div class="form-group text-center">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.place_order')}}">
</div>

 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
</div>
@stop 
@section('footer.append')
<script type="text/javascript">
$(".chb").change(function() {
    $(".chb").prop('checked', false);
    $(this).prop('checked', true);
});

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

    $cbx_group = $("input:checkbox[name='package[]']"); 
    if(! $cbx_group.is(":checked") ){
    	  errors = "You must pickup a package";
    }  
   
	if(errors.length > 0) {
		alert(errors);
		return false;
	}
	return true;
    
  }

 

</script>
 
@stop