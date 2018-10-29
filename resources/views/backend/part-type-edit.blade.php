@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/project-config/part-type/edit/{{$token}}/{{$row->type_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

<div class="form-group input-group">
<div class="input-group-addon">
{{Lang::get('mowork.part_typecode')}}
</div>
<input class="form-control" name="type_code" type="text" value="{{$row->type_code}}" id="typecode" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
{{Lang::get('mowork.type_name')}}
</div>
<input class="form-control" name="name" type="text" value="{{$row->name}}" id="name" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
{{Lang::get('mowork.in_english')}}
</div>
<input class="form-control" name="name_en" type="text" value="{{$row->name_en}}" id="name_en" />
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

    	  type_code = $.trim($('#typecode').val()); 
    	  if(typecode < 1) {
    	     errors += "{{Lang::get('mowork.typecode_required')}} \n";	
    	  }
    	  	 
    	  type_name = $.trim($('#name').val()); 
    	  if(type_name.length < 1) {
    			errors += "{{Lang::get('mowork.typename_required')}} \n";	
    	   }
     
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
           
    	  
          $.ajax({
    	        type:"GET",
    	        url : '/dashboard/project-config/part-type/check-existed-part-type',
    	        data : { part_type_code: type_code, part_type_name: type_name, type_id: {{$row->type_id}} },
    	        async: false,
    	        dataType: 'json',
    	        success : function(result) {
    	           
    	        	for(var ii in result){
    	        		 
    			        if(result[0] == 'existedBoth') {
    		        		   $('#next').css('display', 'none');
    		        		   error = "{{Lang::get('mowork.code_name_existed')}}";
       	      	       	   alert(error); 
       	      	       	   existed = true;  
    		        		   break;
    			        } else if(result[0] == 'existedCode'){
    			        	  error = "{{Lang::get('mowork.code_existed')}}";
    			           	  alert(error);
    			              existed = true;  
    			              break;
    			        } else if(result[0] == 'existedName'){
    			        	  error = "{{Lang::get('mowork.name_existed')}}";
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