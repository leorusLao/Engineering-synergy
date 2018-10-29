@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-12">
    <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add_individual')}}"><span
		class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.add_individual')}}</a>
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
				<th nowrap='nowrap'>{{Lang::get('mowork.employee_code')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.fullname')}}</th>
			 
				<th nowrap='nowrap'>{{Lang::get('mowork.department')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.mobile')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.email')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.position')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.emp_start')}}</th>
				 
				<th nowrap='nowrap'>{{Lang::get('mowork.status')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.action')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.delete')}}</th>
			</tr>
		</thead>

		<tbody>

		 
	    @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->emp_code }}</td>
				<td>{{ $row->fullname }}</td>
				<td>{{ $row->dep_name }}</td>
				<td>{{ $row->mobile }}</td>
				<td>{{ $row->email }}</td>
				<td>{{ $row->position_title }}</td>
				<td>{{ $row->emp_start }}</td>
				 
				<td> 
				   @if ($row->flag == 2) {{Lang::get('mowork.dismiss')}}
				   @elseif ($row->is_active == 0 )  {{Lang::get('mowork.frozen')}}
				   @else {{Lang::get('mowork.normal')}}
				   @endif
				</td>
		 		<td nowrap='nowrap'><a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.edit')}}" href="/dashboard/employee/edit/{{$token}}/{{$row->uid}}"><span class="glyphicon glyphicon-edit"></span></a>
		 		    &nbsp; &nbsp;
		 		    <a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.frozen')}}/{{Lang::get('mowork.anti_frozen')}}" onclick="return confirm('{{Lang::get('mowork.frozen_question')}}')" href="/dashboard/employee/frozen/{{$token}}/{{$row->uid}}"><span class="glyphicon glyphicon-minus-sign"></span></a>
		 		    &nbsp; &nbsp;
					<a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.dismiss')}}" onclick="return confirm('{{Lang::get('mowork.dismiss_question')}}')" href="/dashboard/employee/dismiss/{{$token}}/{{$row->uid}}"><span class="glyphicon glyphicon-remove"></span></a>
				</td>
				<td><a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.delete')}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')" href="/dashboard/employee/delete/{{$token}}/{{$row->uid}}"><span class="glyphicon glyphicon-trash"></span></a></td>	 
			</tr>

		@endforeach

		</tbody>

	</table>

	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm" style="top: 1%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.employee')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/employee/employee-list' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.employee_code')}} *
						</div>
						<input type="text" class="form-control required" name="emp_code"
							placeholder="{{Lang::get('mowork.employee_code')}}"
							title="{{Lang::get('mowork.employee_code')}}" id='emp_code'>
						 
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.fullname')}} *
						</div>
						<input type="text" class="form-control required" name="emp_name"
							placeholder="{{Lang::get('mowork.fullname')}}"
							title="{{Lang::get('mowork.fullname')}}" id='fullname'>
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.mobile')}}
						</div>
						<input type="text" class="form-control required" name="phone"
							placeholder="{{Lang::get('mowork.mobile')}}"
							title="{{Lang::get('mowork.phone')}}" id='phone'>
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.email')}} *
						</div>
						<input type="text" class="form-control required" name="email"
							placeholder="{{Lang::get('mowork.email')}}"
							title="{{Lang::get('mowork.email')}}" id='email'>
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.password')}} *
						</div>
						<input type="text" class="form-control required" name="password"
							placeholder="{{Lang::get('mowork.password_initial')}}"
							title="{{Lang::get('mowork.password_initial')}}" id='password'>
					</div>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.department')}} *
						</div>
					 	{{Form::select('department',$departmentList, '',array('class' => 'form-control', 'id' =>  'department' ))}}
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.position')}}
						</div>
					 	{{Form::select('position_id',$positionList, '',array('class' => 'form-control', 'title' =>  Lang::get('mowork.position') ))}}
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.emp_start')}}
						</div>
						<input type="text" class="form-control" name="emp_start"
							placeholder="{{Lang::get('mowork.emp_start')}}"
							title="{{Lang::get('mowork.emp_start')}}" id='emp_start'>
					</div>
				 		 
					<div class="form-group">
						<input type="submit" class="form-control btn-info" name="submit"
							value="{{Lang::get('mowork.add')}}">
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
					<div class="margin-t20">{{Lang::get('mowork.add_employee_warning')}}</div>
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
    	$(".modal-dialog").draggable({
    	    handle: ".modal-header"
    	}); 
    	
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
    		 
    	  emp_code = $.trim($('#emp_code').val()); 
    	  if(emp_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.empcode_required')}} \n";	
    	  }

    	  fullname = $.trim($('#fullname').val()); 
    	  if(fullname.length< 1) {
    	     errors += "{{Lang::get('mowork.fullname_required')}} \n";	
    	  }

    	  email = $.trim($('#email').val()); 
    	  if(email.length < 1) {
    	     errors += "{{Lang::get('mowork.email_required')}} \n";	
    	  } else {
			if(! validateEmail(email) ) {
				errors += "{{Lang::get('mowork.email_invalid')}} \n";	
			}
       	  }

		  password = $.trim($('#password').val());
		  if(password.length < 6) {
	    	     errors += "{{Lang::get('mowork.password_too_short')}} \n";	
	      }

		  department =  $('#department').val();
		  
		  if(department < 1) {
	    	     errors += "{{Lang::get('mowork.department_required')}} \n";	
	      }
	      
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }

    	  existed = false;
          $.ajax({
    	        type:"GET",
    	        url : '/dashboard/employee/add-employee-check',
    	        data : { emp_code: emp_code, email: email },
    	        async: false,
    	        dataType: 'json',
    	        success : function(result) {
    	          
    	        	for(var ii in result){
    	        		    
    			        if(result[0] == 'existedBoth') {
    		        	   error = "{{Lang::get('mowork.code_email_existed')}}";
       	      	       	   alert(error); 
       	      	       	   existed = true;  
    		        		   break;
    			        } else if(result[0] == 'existedCode'){
    			        	  error = "{{Lang::get('mowork.code_existed')}}";
    			           	  alert(error);
    			              existed = true;  
    			              break;
    			        } else if(result[0] == 'existedEmployee'){
  			        	  error = "{{Lang::get('mowork.existed_employee')}}";
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

  function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email); 
  } 
 
</script>


@stop
