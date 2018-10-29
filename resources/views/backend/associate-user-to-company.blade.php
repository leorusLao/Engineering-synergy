@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
@endif
@if(isset($errors))
	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
@endif
	<h4>{{Lang::get('mowork.associate_help')}}</h4>
	<div>1. {{Lang::get('mowork.associate_warn1')}}</div>
	<div class="marign-b50">2. {{Lang::get('mowork.associate_warn2')}}</div> 
	<form  class="form-horizontal form-inline margin-b20" method="post" action="{{ url('/dashboard/employee/associate-user-to-company') }}"
	  onsubmit="return validateForm()">
	<input name="_token" value="{{ csrf_token() }}" type="hidden">
	<div class="form-group">{{Lang::get('mowork.search_email_phone')}}: <input name="identity" id="identity" class="form-control" type="text"></div>
	<div class="form-group"><input type="submit" name="submit" class="btn btn-sm btn-info form-control" id='submit' value="{{Lang::get('mowork.search')}}"></div>
	</form>
 
    
   @if(count($rows))
     <form  class="form-horizontal form-inline margin-b20" method="post" action="{{ url('/dashboard/employee/associate-user-to-company') }}"
	  onsubmit="return validateForm2()">  
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
			 
				<th>{{Lang::get('mowork.fullname')}}</th>
			  	<th>{{Lang::get('mowork.mobile')}}</th>
				<th>{{Lang::get('mowork.email')}}</th>
				<th>{{Lang::get('mowork.please_select')}}</th>
			</tr>
		</thead>

		<tbody>

		 
	    @foreach($rows as $row)
 
			<tr>
			 
				<td>{{ $row->fullname }}</td>
			 	<td>{{ $row->mobile }}</td>
				<td>{{ $row->email }}</td>
				 
				<td><input type="checkbox" name="cbx[]" value="{{$row->uid}}"></td>
			</tr>

		@endforeach

		</tbody>

	</table>
	<input name="_token" value="{{ csrf_token() }}" type="hidden">
	<div class="text-center"><input type="submit" name="submit" class="btn btn-sm btn-info form-control" id='submit2' value="{{Lang::get('mowork.associate_action')}}"></div>
    </form>        
   @endif
 </div>  
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');
          
 
    });
   

    function validateForm(){
    	  var errors = '';
           
		  identity =  $.trim($('#identity').val()); 

		  if (identity.indexOf('@') !== -1 || identity.length < 5) {
		    	if(! validateEmail(identity)){
		    		errors += "{{Lang::get('mowork.invalid_email')}} \n";	
		        }
		   }
		   else if (! validatePhone(identity)) {
		    	errors += "{{Lang::get('mowork.invalid_mobile')}} \n";	
		  }

	      if(errors.length > 0) {
			alert(errors);
			return false;
	      }
    	  return true;
    	  
    }

    function validateForm2(){
  	  var errors = '';
         
  	     
  	   checkid = $("input[name='cbx[]']:checked").val();
       if(checkid > 0) { 
  	   		return true;
       }
       alert("{{Lang::get('mowork.tick_user')}}");
  	   return false;
  	  
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
