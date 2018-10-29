@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-8 col-sm-offset-2">
 
 <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.part_type')}}"><span
		class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.part_type')}}</a>
 </div>

	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))

    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.part_typecode')}}</th>
				<th>{{Lang::get('mowork.name')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
  				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->type_code }}</td>
				<td>{{ $row->name }}</td>
				<td>{{ $row->name_en }}</td>
			  	<td>
				@if($row->company_id > 0)
				 <a href="/dashboard/project-config/part-type/edit/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			     <a href="/dashboard/project-config/part-type/delete/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')"><span class="glyphicon glyphicon-trash"></span></a>
			    @endif
			    </td>
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

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.part_type')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/project-config/part-type' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.part_typecode')}} *
						</div>
						<input type="text" class="form-control" name="type_code"
							 
							title="{{Lang::get('mowork.part_typecode')}}" id='type_code'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">{{Lang::get('mowork.type_name')}} *
						</div>
						<input type="text" class="form-control" name="name"
							 
							title="{{Lang::get('mowork.type_name')}}" id='name'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}}</i>
						</div>
						<input type="text" class="form-control" name="name_en"
							 
							title="{{Lang::get('mowork.in_english')}}" id='name_en'>
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
   
     });


    function validateForm(){
      var errors = '';
  
      type_code = $.trim($('#type_code').val());
      if(type_code.length < 1) {
        	errors += "{{Lang::get('mowork.typecode_required')}} \n";	
      }
      name = $.trim($('#name').val()); 
      if(name.length < 1) {
      	errors += "{{Lang::get('mowork.typename_required')}} \n";	
      }
          
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
      existed = false;
      $.ajax({
	        type:"GET",
	        url : '/dashboard/project-config/part-type/check-existed-part-type',
	        data : { part_type_code: type_code, part_type_name: name },
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