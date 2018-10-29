@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-8 col-sm-offset-2">
 
 <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.plan_type')}}"><span
		class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.plan_type')}}</a>
 </div>

	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))

    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.plan_typecode')}}</th>
				<th>{{Lang::get('mowork.name')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
  				<th>计划编码前缀</th>
  				<th>计划编码描述</th>
  				<th>计划编码描述(英)</th>
  				<th>计划编码-名称</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif
			
			@foreach($rows as $row)
 
			<tr>
				<td>{{ $row->type_code }}</td>
				<td>{{ $row->type_name }}</td>
				<td>{{ $row->type_name_en }}</td>
				<td>{{ $row->cn_pix }}</td>
				<td>{{ $row->cn_description }}</td>
				<td>{{ $row->cn_description_en }}</td>
				<td>{{ $row->cc_cfg_name }}</td>
			  	<td>
				@if($row->company_id > 0)
				 <a href="/dashboard/project-config/plan-type/edit/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			     <a href="/dashboard/project-config/plan-type/delete/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')"><span class="glyphicon glyphicon-trash"></span></a>
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
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.plan_type')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/project-config/plan-type' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.plan_typecode')}} *
						</div>
						<input type="text" class="form-control" name="type_code"
							 
							title="{{Lang::get('mowork.plan_typecode')}}" id='type_code'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.type_name')}} *
						</div>
						<input type="text" class="form-control" name="type_name"
							 
							title="{{Lang::get('mowork.type_name')}}" id='type_name'>
					</div>
				 	
				 	<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.english_name')}}
						</div>
						<input type="text" class="form-control" name="type_name_en"
							 
							title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
					</div>
					<div class="form-group input-group">
						<div class="input-group-addon">
							计划编码前缀
						</div>
						<input type="text" class="form-control" name="cn_pix"

							   title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
					</div>

					<div class="form-group input-group">
						<div class="input-group-addon">
							计划编码描述
						</div>
						<input type="text" class="form-control" name="cn_description"

							   title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
					</div>

					<div class="form-group input-group">
						<div class="input-group-addon">
							计划编码描述(英)
						</div>
						<input type="text" class="form-control" name="cn_description_en"

							   title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
					</div>

					<div class="form-group input-group">
						<div class="input-group-addon">
							计划编码-名称
						</div>
						<input type="text" class="form-control" name="cc_cfg_name"

							   title="{{Lang::get('mowork.english_name')}}" id='type_name_en'>
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
    	 
      var type_code = $.trim($('#type_code').val()); 
      if(type_code.length < 1) {
      	errors += "{{Lang::get('mowork.typecode_required')}} \n";	
    	}

      var type_name = $.trim($('#type_name').val());
  
      if(type_name < 1) {
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
	        data : { plan_type_code: type_code, plan_type_name: type_name },
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