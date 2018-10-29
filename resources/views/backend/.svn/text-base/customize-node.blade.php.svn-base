@extends('popup-base')
  
@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3 margin-t20">
    
    <h4 class="text-center margin-b20">{{Lang::get('mowork.customized_node')}}</h4>
   	@if($result)
	<div class="text-center text-danger margin-b20">{{$result}}</div>
	<script type="text/javascript">
	 window.opener.location.reload();
    </script>
 	@endif 
	 
    
    <form action='/dashboard/project-config/customize-node/{{$token}}/{{$node_id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="text-center margin-b10">{{$basinfo->node_no}}-{{$basinfo->name}}</div> 
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						 {{Lang::get('mowork.leader')}}
						</div>
						&nbsp;&nbsp;
						<select name="leader">
						<option value="0"></option>
						@foreach($employees as $val)
				 		 <option value="{{$val->uid}}" @if($row->leader == $val->uid) selected @endif>{{$val->fullname}}</option>
				 		@endforeach
						</select>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						 {{Lang::get('mowork.message_via')}}
						</div>
						 
						&nbsp;&nbsp;{{Lang::get('mowork.site_message')}}<input type="checkbox" name="message_via[]" value='site_message' @if(isset($row) && strstr($row->trigger_event,'site_message')) checked @endif> 				 
						&nbsp;&nbsp;{{Lang::get('mowork.small_routine')}}<input type="checkbox" name="message_via[]" value='small_routine' @if(isset($row) && strstr($row->trigger_event,'small_routine')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.sms')}}<input type="checkbox" name="message_via[]" value='sms' @if(isset($row) && strstr($row->trigger_event,'sms')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.email')}}<input type="checkbox" name="message_via[]" value='email' @if(isset($row) && strstr($row->trigger_event,'email')) checked @endif>
					</div>
					
					<div class="form-group input-group">
					     <div class="input-group-addon">
						 {{Lang::get('mowork.node_expandable')}}
						</div>
						&nbsp;&nbsp;<input type="checkbox" name="expandable" @if(isset($row) && $row->expandable) checked @endif> 				 
					</div>
				 		
					<div class="form-group input-group">
					    <a class="btn btn-info" data-toggle="collapse" href="#people1">
						{{Lang::get('mowork.task_advised_people')}} ({{Lang::get('mowork.onoff')}})
						</a>
						<div id="people1" class="collapse">
						<?php if(isset($row)) {
						         $task_people = explode(',', $row->task_people);
						      }
						?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="task_people[]" value="{{$val->uid}}"  @if(isset($task_people) && in_array($val->uid, $task_people)) checked @endif >{{$val->fullname}} {{$val->name}}<br>
				 		@endforeach
				 		</div>
					</div>
			 	 	 
				 	<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.task_text')}}</i>
						</div>
						<textarea  name='task_text' class="form-control" name="resource"
							 id='task_text'>@if(isset($row)) {{$row->task_text}} @endif</textarea>
					</div>
					
					<div class="form-group input-group">
					    <a class="btn btn-info" data-toggle="collapse" href="#people2">
						{{Lang::get('mowork.done_advised_people')}} ({{Lang::get('mowork.onoff')}})
						</a>
						<div id="people2" class="collapse">
						<?php if(isset($row)) {
						         $done_people = explode(',', $row->done_people);
						      }
						?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="done_people[]" value="{{$val->uid}}" @if(isset($done_people) && in_array($val->uid, $done_people)) checked @endif>{{$val->fullname}} {{$val->name}}<br>
				 		@endforeach
				 		</div> 
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
					    <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.done_text')}}</i>
						</div>
						<textarea  name='done_text' class="form-control" name="resource"
							 id='done_text'>@if(isset($row)) {{$row->done_text}} @endif</textarea>
					</div>
					
			 		<div class="form-group input-group margin-l20">
						<input type="submit" class="btn-success btn-sm" name="submit"
							@if(isset($row))
							value="{{Lang::get('mowork.update')}}"
							@else
							value="{{Lang::get('mowork.add')}}"
							@endif
						 >
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
					 
				</form>
 
	             <div class="clearfix"></div>
	               
                 <div class="text-center" onclick="window.close()"><p class="btn">{{Lang::get('mowork.close')}}</p></div>
                  
</div>
 
@stop 

@section('footer.append')
 
<link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
<script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
      	   
      $('#me8').addClass('active');   

  	  $('#mydropzone').click(function(event){
	      	event.preventDefault();
	  	  });
	     
	  Dropzone.options.mydropzone={
	    	maxFiles: 1, 
	      	maxFilesize: 4,
	      	acceptedFiles: ".pdf,.docx,.doc",
	          addRemoveLinks: true,
	          dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.upload_standard')}}</span>",
	          dictFileTooBig: "{{Lang::get('mowork.image_too_big')}}",
	          dictRemoveFile: "{{Lang::get('mowork.cancel_image')}}",
	          dictInvalidFileType: "{{Lang::get('mowork.image_type_error')}}",
	          dictMaxFilesExceeded: "{{Lang::get('mowork.exceed_max_files')}}",
	          init: function() {
	        
	        this.on("maxfilesexceeded", function(file){
	      	 
	             this.removeFile(file);
	        });	
	         
	        this.on("error", function(file, responseText) {
	             alert(responseText);
	             
	             console.log(file);
	        });
	        
	        this.on("success", function(file, responseText) {
	            
	            console.log(file);
	       });
	        
	      },

	      
	      removedfile: function(file) {
	     	 
	          var name = file.name;  
	          
	        	$.ajax({
	          	type: 'POST',
	          	url: "{{url('/relink')}}",
	          	 
	          	 data: {
	                   fname: name,//fullpath for this uploaded file to be deleted
	                   _token: "{{ csrf_token() }}" 
	              },
	              success: function( data ) {
	              },
	              error: function(xhr, status, error) {
	                   alert(error);
	              },
	              dataType: 'html'  //use type html rather than json in order to post token 
	      	});
	      	 
	  		var _ref;
	  		return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
	        }
	     }
             

     });


    function validateForm(){
      var errors = '';
    	 
      $cbx_via = $("input:checkbox[name='message_via[]']"); 
      if(! $cbx_via.is(":checked") ){
      	  errors = "{{Lang::get('mowork.message_via_required')}}\n";
      }  

      $cbx_task = $("input:checkbox[name='task_people[]']"); 
      if(! $cbx_task.is(":checked") ){
      	  errors += "{{Lang::get('mowork.people_required')}}\n";
      }  
       
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
       
      return true;
      
    }
  
   	
</script>


@stop