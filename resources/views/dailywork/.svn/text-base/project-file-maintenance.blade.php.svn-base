@extends('backend-base')
@section('css.append')
  
@stop
@section('content')

<div class="col-xs-12">
   <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.upload_file')}}"><span><b>{{Lang::get('mowork.upload_file')}}</b></span></a>
    </div>
    @if(Session::has('result'))
	<div class="text-center text-danger marging-b20" class="close" data-dismiss="alert">{{Session::get('result')}}</div>
	@endif
	 
		<div class="panel panel-primary">
			 
	  		<div class="panel-body">
	  			<div class="row">
	  			 
	  					<div class="margin-b10"><b>{{Lang::get('mowork.project_number')}}</b>: {{$basinfo->proj_code}}</div>
				        
				           <div class="table-responsive table-scrollable">
			               <table class="table table-bordered">	 
	        <thead>
			<tr>
				<th nowrap='nowrap'>{{Lang::get('mowork.folder')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.filename')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.filesize')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.created_date')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.last_updated_time')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.update_times')}}</th>
			    <th nowrap='nowrap' class="text-center">{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		    </thead>		<tbody>          
				            @foreach($rows as $row)
				             
				             @if($row->level == 1)
				             <tr>
				             <td nowrap='nowrap'><img src="/asset/images/folder.png" style="height:20px"> {{Lang::get("mowork.$row->title")}}</td><td></td>
				             <td></td><td></td><td></td><td></td><td></td>
				             </tr>
				             @else 
				             <tr>
				             <td>{{$row->part_name}}</td><td>{{$row->title}}</td><td>{{$row->fsize}}</td><td>{{substr($row->created_at,0,10)}}</td>
				             <td>{{$row->updated_at}}</td><td>{{$row->version}}</td>
				             <td><a href="/dashboard/file-view/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}">{{Lang::get('mowork.view')}}</a>&nbsp;&nbsp; 
				                 <a href="/dashboard/file-delete/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')">{{Lang::get('mowork.delete')}}</a>&nbsp;&nbsp;  
				                 <a href="/dashboard/file-download/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}">{{Lang::get('mowork.download')}}</a>
				             </td>
				             </tr>
				             @endif
				              
				            @endforeach
				            </tbody>	  
				           </table>
 	  				      </div>
	  			</div>
 	  			
	  		</div>
        </div>
 
</div>

  
 <div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.upload_file')}}</h4>
			</div>
            <div class="modal-body">
                <div class="text-left">{{Lang::get('mowork.select_area')}}</div>
                <div class="margin-b20">
                    <form action="{{ url('/upload/instruction') }}" class="dropzone" id="mydropzone" style="min-height: 20px;">
                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    </form>
                </div>
                {{ Form::open(['url'=>"/dashboard/project/file-maintenance/$token/$project_id",'onsubmit' => 'return validate()'])}}
                    <div class="form-group">
                        <button class="btn btn-info">{{Lang::get('mowork.upload_file')}}</button>
                        <input type="hidden" name="submit" value="submit">
                    </div>
                {!! Form::close() !!}
			</div>
			<div class="modal-footer"></div>
			<div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div>
        </div>
    </div>
	</div>
 
 
@stop 
@section('footer.append')
 
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
	  //$( "#sortable" ).sortable();
	  //$( "#sortable" ).disableSelection();
	  
	  $(".modal-dialog").draggable({
  	    handle: ".modal-header"
  	  });
 
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
 	        	 alert("{{Lang::get('mowork.upload_file_error')}}");
 	        	 this.removeFile(file);
 	             //console.log(file);
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
