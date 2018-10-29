@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
<div class="margin-b20">
	<a href='#formholder' onclick="addForm()" rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add')}}{{Lang::get('mowork.department')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add')}}{{Lang::get('mowork.department')}}</span></a>
</div>
    
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
    @if(count($rows))
	<div class="table-responsive table-scrollable">
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.department_code')}}</th>
				<th>{{Lang::get('mowork.department_name')}}</th>
				<th>{{Lang::get('mowork.upper_department')}}</th>
				<th>{{Lang::get('mowork.manager')}}</th>
				<th>{{Lang::get('mowork.action')}}</th>
			</tr>
		</thead>
		<tbody>
	 
	    @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->dep_code }}</td>
				<td>{{ $row->name }}</td>
				<td>
				  @foreach ($departmentList as $key => $val)
				     @if($key == $row->upper_id)
				        {{$val}}
				     @endif
				  @endforeach
				</td>
				<td>
				  @foreach ($employees as $man)
				     @if($man->uid == $row->manager)
				        {{$man->fullname}}
				     @endif
				  @endforeach
				</td>
		 		<td><a rel="tooltip" data-placement="right" data-original-title="Edit" href="/dashboard/department/edit/{{hash('sha256',$salt.$row->dep_id)}}/{{$row->dep_id}}"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					<a rel="tooltip" data-placement="right" data-original-title="Delete" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')" href="/dashboard/department/delete/{{hash('sha256',$salt.$row->dep_id)}}/{{$row->dep_id}}"><span class="glyphicon glyphicon-trash"></span></a>
				</td>			 
			</tr>

		@endforeach
		</tbody>
	</table>

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
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.department')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/department-setup' method='post'
					autocomplete='off' role=form onsubmit='return checkExistedDepartement();'>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.department_code')}}
						</div>
						<input type="text" class="form-control required" name="dep_code"
							placeholder="{{Lang::get('mowork.department_code')}}"
							title="{{Lang::get('mowork.department_code')}}" id='dep_code'>
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.department_name')}}
						</div>
						<input type="text" class="form-control required" name="dep_name"
							placeholder="{{Lang::get('mowork.department_name')}}"
							title="{{Lang::get('mowork.department_name')}}" id='dep_name'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.upper_department')}}
						</div>
						{{Form::select('upper',$departmentList, '',array('class' => 'form-control', 'id' =>  'upper' ))}}
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.manager')}}
						</div>
						{{Form::select('manager',$employeeList, '',array('class' => 'form-control', 'id' =>  'manager' ))}}
						 
					</div>
	 	 			 
					<div class="form-group input-group">
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
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
    		 
    	  dep_code = $.trim($('#dep_code').val()); 
    	  if(dep_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.depcode_required')}} \n";	
    		}

    	  dep_name = $.trim($('#dep_name').val()); 
    	  if(dep_name < 1) {
    	     errors += "{{Lang::get('mowork.depname_required')}} \n";	
    		}
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
   }

    function checkExistedDepartement() {
  	  dep_code = $('#dep_code').val();
  	  dep_name = $('#dep_name').val();
      existed = false;
 	  errors = '';
 	  
 	  
  	  $.ajax({
  	        type:"GET",
  	        url : '/dashboard/check-existed-department',
  	        data : { dep_code: dep_code, dep_name: dep_name },
  	        async: false,
  	        dataType: 'json',
  	        success : function(result) {
  	       
  	        	for(var ii in result){
  	        		 
  			        if(result[0] == 'existedBoth') {
  		        		   $('#next').css('display', 'none');
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

        if(dep_code.length < 1) {
      	  	errors += "{{Lang::get('mowork.depcode_required')}} \n";	
      	  }

      	 if(dep_name < 1) {
      	    errors += "{{Lang::get('mowork.depname_required')}} \n";	
      	 }
      	  
      	 if(errors.length > 0) {
      		alert(errors);
      		return false;
      	 }
        
    	return true;
    }
    
</script>


@stop
