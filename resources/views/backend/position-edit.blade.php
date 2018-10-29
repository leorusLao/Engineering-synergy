@extends('backend-base')

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20" id="result">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/employee/position/edit/{{$token}}/{{$row->position_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.position')}}</i>
</div>
<input class="form-control" name="position" type="text" value="{{$row->position_title}}" id="position" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}}</i>
</div>
<input class="form-control" name="position_en" type="text" value="{{$row->position_title_en}}" id="position_en" />
</div>

<div class="text-center text-danger margin-b20" id="dup"></div>

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
    	 $('#dup').text('');
    	 $('#result').text('');
         var position = $.trim($('#position').val()); 
          
         if(position.length < 1) {
         	errors += "{{Lang::get('mowork.position_required')}} \n";	
         }
         position_en = $.trim($('#position_en').val()); 

         if(errors.length > 0) {
       		alert(errors);
       		return false;
         }
     
         $existed = false;
         $.ajax({
   	        type:"GET",
   	        url : '/dashboard/employee/check-existed-position',
   	        data : { position: position, position_en: position_en, position_id: {{$row->position_id}} },
   	        async: false,
   	        dataType: 'json',
   	        success : function(result) {
   	           
   	        	for(var ii in result){
   	        		if(result[0] == 'existed'){
   			        	  error = "{{Lang::get('mowork.position_existed')}}";
   	  			          $('#dup').text(error);
   	  			          existed = true;  
   	  			          break;
   			        } 
   	        	}
   	        },
   	        error: function() {
   	            //do logic for error
   	        	 alert('failed');
   	        	 return false; 
   	        }
   	    });
     
           if(existed) return false;
           
           return true;
      }
    
</script>


@stop