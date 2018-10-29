@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-12">
    @if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif 
	@if(count($rows))

    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.node_code')}}</th>
				<th>{{Lang::get('mowork.node').Lang::get('mowork.name')}}</th>
				 
  				<th nowrap='nowrap'>{{Lang::get('mowork.filename')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.filesize')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.created_date')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.update_times')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.last_updated_time')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.upload_file')}}</th>
			    <th nowrap='nowrap' class="text-center">{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif
			
			<?php $last_id = 0;?>
			@foreach($rows as $row)
 
			<tr>
			    @if($row->node_id != $last_id)
				<td>{{ $row->node_no }}</td>
				<td>{{ $row->name}}</td>
				@else
				<td></td>
				<td></td>
				@endif
				
				<td>{{$row->filename}}</td>
			  	<td>{{$row->fsize}}</td>
			    <td>{{substr($row->created_at,0,10)}}</td>
			    <td>{{$row->version}}</td>
			    <td>{{substr($row->updated_at,0,10)}}</td>
			    <td class="text-center"><a href='#formholder' data-toggle="modal" data-book-id={{$row->node_id}}><span class="glyphicon glyphicon-cloud-upload"></span></a></td>
			    <td class="text-center">@if($row->filename)
			        <a href="/dashboard/node-file/view/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" target='_blank'>{{Lang::get('mowork.view')}}</a>&nbsp;&nbsp; 
				    <a href="/dashboard/node-file/delete/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')">{{Lang::get('mowork.delete')}}</a>&nbsp;&nbsp;  
				    <a href="/dashboard/node-file/download/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}">{{Lang::get('mowork.download')}}</a>
				    @endif
				</td>
				<?php $last_id = $row->node_id;?>
			</tr>

			@endforeach 
			
	@if(count($rows))

		</tbody>

	</table>
    </div>
    
	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
	@endif

</div>

<div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.upload_file')}}</h4>
			</div>
			
		<div class="modal-body">
		  <div class="text-left">{{Lang::get('mowork.select_area')}}</div>
		   
	    <div class="margin-b20">
		<form action="{{ url('/upload/instruction') }}" class="dropzone"
			id="mydropzone" style="min-height: 20px;">
			<input name="_token" value="{{ csrf_token() }}" type="hidden">
		</form>
	</div>
	{{ Form::open(['url'=>"/dashboard/node/instruction-document",'onsubmit' => 'return	validate()'])}}
 
	<div class="form-group">
		<button class="btn btn-info">{{Lang::get('mowork.confirm')}}</button>
		<input type="hidden" name="submit" value="submit">
		<input type="hidden" name="node_id" id="node_id">
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
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
      	   
      $('#me8').addClass('active');

      $('#formholder').on('show.bs.modal', function(e) {
          var bookId = $(e.relatedTarget).data('book-id');
          $(e.currentTarget).find('input[name="node_id"]').val(bookId);
      });
      
  	  $('#mydropzone').click(function(event){
	      	event.preventDefault();
	  	  });
	     
	  Dropzone.options.mydropzone={
	    	maxFiles: 4, 
	      	maxFilesize: 4,
	      	acceptedFiles: ".pdf,.docx,.doc,.jpg,.png,.gif",
	          addRemoveLinks: true,
	          dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.upload_node_file')}}</span>",
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
      errors = '';

      node_code = $.trim($('#node_code').val());	 
      type_id = $.trim($('#type_id').val()); 
      node_name = $.trim($('#node_name').val());

      if(node_code.length < 1) {
        errors += "{{Lang::get('mowork.nodecode_required')}} \n";	
      }
      
      if(type_id.length < 1) {
      	errors += "{{Lang::get('mowork.typename_required')}} \n";	
      }

      if(node_name.length < 1) {
          errors += "{{Lang::get('mowork.nodename_required')}} \n";	
      }
       
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
      return true;
      
    }

    function popwin(str)
    {    
    	   var left = (screen.width/2)-(440/2);
           var top = (screen.height/2)-(500/2);
            
    	   window.open("/dashboard/project-config/customize-node/" + str, 'win'+str, 'height=500,width=440,top='+top+', left='+left);
  	 
    } 
   	
</script>


@stop