@extends('backend-base') 
 
@section('content')
<div class="col-xs-12 col-sm-6 col-sm-offset-3">

<h4 class="text-danger text-center">{{Lang::get('mowork.order_history')}}</h4>
@if(Session::has('result'))
	 <div class="alert alert-warning">
          {{Session::get('result')}}
     </div>
@endif

  <div class="text-center">TODO</div>
 
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