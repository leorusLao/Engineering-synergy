@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/project-config/plan-type/edit/{{$token}}/{{$row->type_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.plan_typecode')}}</i>
</div>
<input class="form-control" name="type_code" type="text" value="{{$row->type_code}}" id="typecode" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.type_name')}}</i>
</div>
<input class="form-control" name="type_name" type="text" value="{{$row->type_name}}" id="type_name" />
</div>

			<div class="form-group input-group">
				<div class="input-group-addon">
					<i class="livicon" data-name="doc-portrait" data-size="18"
						data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.english_name')}}</i>
				</div>
				<input type="text" class="form-control" name="type_name_en" value="{{$row->type_name_en}}"
					title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
			</div>

	<div class="form-group input-group">
		<div class="input-group-addon">
			<i class="livicon" data-name="doc-portrait" data-size="18"
			   data-c="#000" data-hc="#000" data-loop="true">计划编码描述</i>
		</div>
		<input type="text" class="form-control" name="cn_description" value="{{$row->cn_description}}"
			   title="计划编码描述" id='type_name_en'>
	</div>

	<div class="form-group input-group">
		<div class="input-group-addon">
			<i class="livicon" data-name="doc-portrait" data-size="18"
			   data-c="#000" data-hc="#000" data-loop="true">计划编码描述(英)</i>
		</div>
		<input type="text" class="form-control" name="cn_description_en" value="{{$row->cn_description_en}}"
			   title="计划编码描述(英)" id='type_name_en'>
	</div>

	<div class="form-group input-group">
		<div class="input-group-addon">
			<i class="livicon" data-name="doc-portrait" data-size="18"
			   data-c="#000" data-hc="#000" data-loop="true">计划编码前缀</i>
		</div>
		<input type="text" class="form-control" name="cn_pix" value="{{$row->cn_pix}}"
			   title="计划编码前缀" id='type_name_en'>
	</div>

	<div class="form-group input-group">
		<div class="input-group-addon">
			<i class="livicon" data-name="doc-portrait" data-size="18"
			   data-c="#000" data-hc="#000" data-loop="true">计划编码-名称</i>
		</div>
		<input type="text" class="form-control" name="cc_cfg_name" value="{{$row->cc_cfg_name}}"
			   title="计划编码-名称" id='type_name_en'>
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
    	  if(type_code < 1) {
    	     errors += "{{Lang::get('mowork.typecode_required')}} \n";	
    	  }
    	  	 
    	  var type_name = $.trim($('#type_name').val());
        
          if(type_name < 1 ) {
             errors += "{{Lang::get('mowork.typename_required')}} \n";	
          }
     
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }

    	  existed = false;
          $.ajax({
    	        type:"GET",
    	        url : '/dashboard/project-config/plan-type/check-existed-plan-type',
    	        data : { plan_type_code: type_code, plan_type_name: type_name, type_id: {{$row->type_id}} },
    	        async: false,
    	        dataType: 'json',
    	        success : function(result) {
    	           
    	        	for(var ii in result){
    	        		    
    			        if(result[0] == 'existedBoth') {
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