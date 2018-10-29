@extends('backend-base')
@section('css.append')
 
<link href="/asset/css/treeview.css" rel="stylesheet">
@stop
@section('content')

<div class="col-xs-12">

    @if(Session::has('result'))
	<div class="text-center text-danger marging-b20" class="close" data-dismiss="alert">{{Session::get('result')}}</div>
	@endif
	@if(isset($errors))
		<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
	@endif
	 
		<div class="panel panel-primary">
			 
	  		<div class="panel-body">
	  			<div class="row">
	  				<div class="col-md-6">
	  					<h4>{{Lang::get('mowork.folder')}}</h4>
				        <ul id="tree1" class="tree">
				           
				            @foreach($pfolders as $category)
				                <li>
				                    @if(count($category->childs))
				                    <a class="fa fa-fw fa-plus-square-o">{{ $category->title }}</a>
				                    @else
				                     {{ $category->title }}
				                    @endif
				                    @if(count($category->childs))
				                        @include('dailywork.folder-child',['childs' => $category->childs])
				                    @endif
				                </li>
				            @endforeach
				        </ul>
	  				</div>
	  				<div class="col-md-6">
	  					 
                           <div class="margin-b20">
                           <form action="{{ url('/upload/instruction') }}" class="dropzone" id="mydropzone" style="min-height: 20px;">
                           <input name="_token" value="{{ csrf_token() }}" type="hidden">
                           </form>
                           </div>
				  			{{ Form::open(['url'=> "/dashboard/file-maintenance/$token/$detail_id",'onsubmit' => 'return validate()'])}}
 
								<div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
									{{ Form::label("".Lang::get('mowork.folder_category').":") }}
									 
									<select name='folder_cat' id="folder_cat">
									   <option value="">{{Lang::get('mowork.please_select')}}</option>
									   <option value="1">{{Lang::get('mowork.project')}}</option>
									   <option value="2">{{Lang::get('mowork.plan')}}</option>
									   <option value="3">{{Lang::get('mowork.openissue')}}</option>
									   <option value="4">{{Lang::get('mowork.management')}}</option>
									   <option value="5">{{Lang::get('mowork.other')}}</option>
									</select>
									
									<span class="text-danger">{{ $errors->first('parent_id') }}</span>
								</div>

								<div class="form-group">
									<button class="btn btn-info">{{Lang::get('mowork.upload_file')}}</button>
									<input type="hidden" name="submit" value="submit">
								</div>

				  			{!! Form::close() !!}
      
	  				</div> 
	  			</div>

	  			
	  		</div>
        </div>
 
</div> 
 
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/treeview.js"></script>
<link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
<script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
<script type="text/javascript">


  function validate() {
     
     err = '';
     
     cat = $('#folder_cat').val();
     if(cat.length == 0 ) {
        err += "{{Lang::get('mowork.folder_required')}}\n";
     }
    
     if(err.length > 0 ) {
        alert(err);
        return false;
     }    

     return true;  
  }
    $(function(){
    	 $('#mydropzone').click(function(event){
 	      	event.preventDefault();
 	  	  });
 	     
 	  Dropzone.options.mydropzone={
 	    	maxFiles: 5, 
 	      	maxFilesize: 4,
 	      	acceptedFiles: ".pdf,.docx,xlsx.jpg,.gif,.png",
 	          addRemoveLinks: true,
 	          dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.click_upload')}}</span>",
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

    $("[rel=tooltip]").tooltip({animation:false});

   
</script>
 
@stop
