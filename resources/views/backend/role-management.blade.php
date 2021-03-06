@extends('backend-base') 
  
@section('content')
 <div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
<div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.self_def_role')}}"><span
		class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.add'). Lang::get('mowork.self_def_role')}}</a>
 </div>
   @if(Session::has('result'))
   <div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
   @endif
 
   <table class="table data-table table-striped table-bordered  sort table-responsive">

          <thead>

            <tr>
              <th>{{Lang::get('mowork.role_code')}}</th>
              <th>{{Lang::get('mowork.role_name')}}</th>
              <th>{{Lang::get('mowork.in_english')}}</th>
              <th>{{Lang::get('mowork.limit').Lang::get('mowork.quantity')}}</th>
              <th>{{Lang::get('mowork.assistant').Lang::get('mowork.quantity')}}</th>
              <th>{{Lang::get('mowork.grade')}}</th>
              <th>{{Lang::get('mowork.description')}}</th>
              <th>{{Lang::get('mowork.maintenance')}}</th>   
            </tr>

          </thead>

          <tbody>
  
  @foreach($rows as $row) 
   
    <tr> 
    <td>{{{ $row->role_code }}}</td>
    <td>{{{ $row->role_name }}}</td>
    <td>{{{ $row->english }}}</td>
    <td>@if($row->total == -1){{Lang::get('mowork.have_no').Lang::get('mowork.limit')}}@else{{$row->total}}@endif</td>
    <td>{{{ $row->assistant_num }}}</td>
    <td>@if($row->grade == 0){{Lang::get('mowork.have_no').Lang::get('mowork.grade')}}@else{{$row->total}}@endif</td>
    <td>{{{ $row->role_description }}}</td>
    <td>@if($row->company_id > 0)
        <a href='#updateform' data-toggle="modal" data-book-id={{$row->role_id}}><span class="glyphicon glyphicon-edit"></span></a>
        @endif
    </td>
    </tr>

  @endforeach
 
 
     </tbody>

     </table>

 </div>
     
<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.self_def_role')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/role-management' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group">
						<input type="text" class="form-control required" name="role_code"
							placeholder="{{Lang::get('mowork.role_code')}}"
							title="{{Lang::get('mowork.role_code')}}" id='role_code'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="role_name"
							placeholder="{{Lang::get('mowork.role_name')}}"
							title="{{Lang::get('mowork.role_name')}}" id='role_name'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="description"
							placeholder="{{Lang::get('mowork.description')}}"
							title="{{Lang::get('mowork.description')}}" id='description'>
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



<div class="modal fade" id="updateform" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.update').Lang::get('mowork.self_def_role')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/role-management' method='post'
					autocomplete='off' role=form onsubmit='return validateForm2();'>
					<div class="form-group">
						<input type="text" class="form-control required" name="role_code"
							value=""
							title="{{Lang::get('mowork.role_code')}}" id='rolecode'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="role_name"
							value=""
							title="{{Lang::get('mowork.role_name')}}" id='rolename'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="description"
							value=""
							title="{{Lang::get('mowork.description')}}" id='roledes'>
					</div>
				  		 
					<div class="form-group">
						<input type="submit" class="form-control btn-info" name="update"
							value="{{Lang::get('mowork.update')}}">
					</div>
					<input name="role_id" type="hidden" value="" id="role_id">
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
        $('#updateform').on('show.bs.modal', function(e) {
            role_id = $(e.relatedTarget).data('book-id');
            $('#role_id').val(role_id);
             
            $.ajax({
    	        type:"POST",
    	        url : '{{url("/dashboard/get-role-info")}}',
    	        data : { role_id: role_id, _token: "{{csrf_token()}}" },
    	        dataType: 'json',
    	        success : function(result) {
        	        //alert(JSON.stringify(result));
    	         	for(var ii in result){
        	         	 if(ii == 1) {
            	         	$('#rolecode').val(result[ii]);
            	         } else if (ii == 2) {
            	        	$('#rolename').val(result[ii]);
                	     } else if (ii == 3) {
                	    	$('#roledes').val(result[ii]);
                	     }
    	        	}
 
    	        },
    	        error: function(xhr, status, error) {
     	            //do logic for error
     	            alert(error);
     	        }
    	    }); 
            
        });


    });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
    		 
    	  role_code = $.trim($('#role_code').val()); 
    	  if(role_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.rolecode_required')}} \n";	
    	  }

    	  role_name = $.trim($('#role_name').val()); 
    	  if(role_name.length< 1) {
    	     errors += "{{Lang::get('mowork.rolename_required')}} \n";	
    	  }

    	  description = $.trim($('#description').val()); 
    	  if(description.length < 1) {
    	     errors += "{{Lang::get('mowork.description_required')}} \n";	
    	  }  
		  
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }

    function validateForm2(){
  	  var errors = '';
  		 
  	  role_code = $.trim($('#rolecode').val()); 
  	  if(role_code.length < 1) {
  	  	errors += "{{Lang::get('mowork.rolecode_required')}} \n";	
  	  }

  	  role_name = $.trim($('#rolename').val()); 
  	  if(role_name.length< 1) {
  	     errors += "{{Lang::get('mowork.rolename_required')}} \n";	
  	  }

  	  description = $.trim($('#roledes').val()); 
  	  if(description.length < 1) {
  	     errors += "{{Lang::get('mowork.description_required')}} \n";	
  	  }  
		  
  	  
  	  if(errors.length > 0) {
  		alert(errors);
  		return false;
  	  }
  	  return true;
  	  
  }
  
</script>


@stop
