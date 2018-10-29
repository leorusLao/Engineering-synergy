@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12 col-sm-8 col-sm-offset-2">
    <div class="margin-b20">
	<a href='#formholder'  rel="tooltip"
		data-placement="right"  data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add')}}{{Lang::get('mowork.tool_file')}}"><span
		class="glyphicon glyphicon-plus"></span></a>
    </div>
	@if(Session::has('result'))
	<div class="text-center text-danger">{{Session::get('result')}}</div>
	@endif @if(count($rows))
	<div class="table-responsive table-scrollable margin-t20">
    <table class="table data-table table-condensed table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.folder_type')}}</th>
				<th>{{Lang::get('mowork.file_type')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
	 		</tr>
		</thead>

		<tbody>

			@endif 
			@foreach($rows as $row)
 
			<tr>

				<td>{{ $row->folder_code }}</td>
				<td>{{ $row->filetype }}</td>
				<td>{{ $row->filetype_en }}</td>
				<td>@if($row->company_id > 0)
				    <a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.edit')}}" href="/dashboard/other-setup/tool-file/edit/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					<a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.delete')}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')" href="/dashboard/other-setup/tool-file/delete/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-trash"></span></a>
				   @endif
				</td>
			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>
    </div>
    
	<div class='text-center'><?php echo $rows->links(); ?></div>
	<div class="clearfix"></div>
	@endif

</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.tool_file')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/other-setup/tool-file' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group">
						<input type="text" class="form-control" name="folder_code"
							placeholder="{{Lang::get('mowork.folder_type')}}"
							title="{{Lang::get('mowork.folder_type')}}" id='folder_code'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="filetype"
							placeholder="{{Lang::get('mowork.file_type')}}"
							title="{{Lang::get('mowork.file_type')}}" id='filetype'>
					</div>
					
					<div class="form-group">
						<input type="text" class="form-control" name="filetype_en"
							placeholder="{{Lang::get('mowork.english_name')}}"
							title="{{Lang::get('mowork.english_name')}}" id='english_name'>
					</div>
	 	 			 
					<div class="form-group">
						<input type="submit" class="form-control btn-info" name="submit"
							value="{{Lang::get('mowork.add')}}">
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
				</form>
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

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $("[rel=tooltip]").tooltip({animation:false});
    
    $(function(){
    	 
        $('#me8').addClass('active');   
        /*
         offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2
	      
          $('html,body').animate({
        	   scrollTop: offset > 0 ? offset:1000
          }, 200);
       */
      });


    function validateForm(){
  	  var errors = '';
  		 
  	  var folder_code = $.trim($('#folder_code').val()); 
  	  if(folder_code.length < 1) {
  	  	errors += "{{Lang::get('mowork.foldertype_required')}} \n";	
  		}

  	  var filetype = $.trim($('#filetype').val()); 
  	  if(filetype < 1) {
  	     errors += "{{Lang::get('mowork.filetype_required')}} \n";	
  		}
  	  
  	  if(errors.length > 0) {
  		alert(errors);
  		return false;
  	  }
  	  return true;
   }

</script>


@stop