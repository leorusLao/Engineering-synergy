@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif

<form action="/dashboard/employee/edit/{{$token}}/{{$row->uid}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.employee_code')}}</i>
</div>
<input class="form-control" name="emp_code" type="text" value="{{$row->emp_code}}" id="emp_code" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.fullname')}}</i>
</div>
<input class="form-control" name="fullname" type="text" value="{{$row->fullname}}" id="fullname" />
</div>

 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.mobile')}}</i>
</div>
<input class="form-control" name="phone" type="text" value="{{$row->mobile}}" id="phone" />
</div>
  					
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.email')}}</i>
</div>
<input class="form-control" name="email" type="text" value="{{$row->email}}" id="email" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
 
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.position')}}</i>
</div>
{{Form::select('position_id', $positionList, $row->position_id,array('class' => 'form-control'))}}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
 
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.department')}}</i>
</div>
{{Form::select('dep_id', $departmentList, $row->dep_id,array('class' => 'form-control'))}}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.emp_start')}}</i>
</div>
<input class="form-control" name="emp_start" type="text" value="{{$row->emp_start}}"  />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.emp_end')}}</i>
</div>
<input class="form-control" name="emp_end" type="text" value="{{$row->emp_end}}"  />
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
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
    	 
    	  emp_code = $.trim($('#emp_code').val()); 
    	  if(emp_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.empcode_required')}} \n";	
    		}

    	  fullname = $.trim($('#fullname').val()); 
    	  if(fullname < 1) {
    	     errors += "{{Lang::get('mowork.fullname_required')}} \n";	
    		}

    	  email = $.trim($('#email').val()); 
    	 
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }

    	  existed = false;
          $.ajax({
    	        type:"GET",
    	        url : '/dashboard/employee/add-employee-check',
    	        data : { emp_code: emp_code, email: email, uid: {{$row->uid}} },
    	        async: false,
    	        dataType: 'json',
    	        success : function(result) {
    	          
    	        	for(var ii in result){
    	        		    
    			        if(result[0] == 'existedBoth') {
    		        	   error = "{{Lang::get('mowork.code_email_existed')}}";
       	      	       	   alert(error); 
       	      	       	   existed = true;  
    		        		   break;
    			        } else if(result[0] == 'existedCode'){
    			        	  error = "{{Lang::get('mowork.code_existed')}}";
    			           	  alert(error);
    			              existed = true;  
    			              break;
    			        } else if(result[0] == 'existedEmployee'){
  			        	  error = "{{Lang::get('mowork.existed_employee')}}";
			           	  alert(error);
			              existed = true;  
			              break;
			            } 
    	        	}
    	        },
    	        error: function() {
    	            //do logic for error
    	        	 alert('failed');
    	        	  
    	        }
    	    });
           
            if(existed) return false;
    	  
    	    return true;
    	  
    }
    
</script>


@stop