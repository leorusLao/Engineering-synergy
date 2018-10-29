@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3">
 
@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
@endif
@if(isset($errors))
	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
@endif
	<h4>{{Lang::get('mowork.group_add_help')}}</h4>
	<div>1. {{Lang::get('mowork.file_click')}}; {{Lang::get('mowork.email_as_account')}}
        (<a href="/asset/images/emp-upload-format.png" target='_blank'>{{Lang::get('mowork.file_sample')}}</a>)	
	</div>
	<div>2. {{Lang::get('mowork.batch_password')}}</div> 
	<div class="marign-b20">3. {{Lang::get('mowork.load_to_database')}}</div> 
	<div class="text-center marign-b20" id="processing" style="display: none" ><img src="/asset/img/loading.gif">Please Wait...</div>
    <div class="text-center text-danger" id="personsAdded"></div>
    <form action="{{ url('/upload/employee-list') }}" class="dropzone" id="mydropzone" style="min-height: 50px; margin-top: 20px">
	<input name="_token" value="{{ csrf_token() }}" type="hidden">
	</form>
    <div id="load2Table" style="display: none">
    <input name="password" value="" type="text" class="form-control" placeholder="{{Lang::get('mowork.password_initial')}}" id="password" style="width:140px"><br>
    <div class="form-group">
    <button name='submit' class="btn btn-sm btn-info margin-t10" id='loadRequest'>{{Lang::get('mowork.load_to_database')}}</button>
    </div>
    </div>
</div>
 
@stop

@section('footer.append')
<link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
<script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');

        $('#mydropzone').click(function(event){
	      	event.preventDefault();
	    });

        $('#loadRequest').click(function(event){
         
        	if(! validateForm() ) return; 
        	$('#processing').css('display','block');
        	password = $('#password').val();
        	 
        	 $.ajax({
     	        type:"POST",
     	        url : '{{url("/dashboard/employee/group-add")}}',
     	        data : {submit: "submit", password: password, _token: "{{csrf_token()}}" },
     	        dataType: "json",
     	        success : function(result) {
     	        	var txt = "{{Lang::get('mowork.add_employees')}}";
     	        	for(var ii in result){
     	        		txt +=  result[ii];
     	        	}
     	        	 $('#processing').css('display','none');
     	             $('#personsAdded').html(txt);
     	        },
     	       error: function(xhr, status, error) {
     	    	  alert(error);
               },
     	    });

	    });
	     
	  Dropzone.options.mydropzone={
	    	maxFiles: 1, 
	      	maxFilesize: 4,
	      	acceptedFiles: ".csv,.xlsx,.xls",
	          addRemoveLinks: true,
	          dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.upload_employee_list')}}</span>",
	          dictFileTooBig: "{{Lang::get('mowork.image_too_big')}}",
	          dictRemoveFile: "{{Lang::get('mowork.cancel_file')}}",
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
		        
		        $('#load2Table').css('display','block');
	            console.log(file);
	       });
	        
	      },

	      
	      removedfile: function(file) {
	     	 
	          var name = file.name;  
	          
	        	$.ajax({
	          	type: 'POST',
	          	url: "{{url('/relink/license')}}",
	          	 
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

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
     
		  password =  $.trim($('#password').val()); 
    	  if(password.length < 6) {
     	     errors += "{{Lang::get('mowork.password_too_short')}} \n";	
     	  }
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }

    	  $('#processing').css('dispaly','block');
    	  return true;
    	  
    }

   
</script>


@stop

