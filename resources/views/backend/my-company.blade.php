@extends('backend-base') 
  
@section('content')
 <div class="col-xs-12 col-sm-8 col-sm-offset-2">
 
<div class="margin-b20">
	<a href='/dashboard/company-creation'><span class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.create_company')}}</a>
 </div>
   @if(Session::has('result'))
   <div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
   @endif
 
   @if(count($rows))
   <table class="table data-table table-striped table-bordered  sort table-responsive">

          <thead>

            <tr>
              <th>{{Lang::get('mowork.company_name')}}</th>
              <th>{{Lang::get('mowork.bu_name')}}</th>
              <th>{{Lang::get('mowork.bu_site')}}</th>
              <th>{{Lang::get('mowork.maintenance')}}</th>
              <th>{{Lang::get('mowork.companysite_entry')}}</th>
            </tr>

          </thead>

          <tbody>
  @else
    <div class="text-center">{{Lang::get('mowork.monk')}}</div>
  @endif
  @foreach($rows as $row) 
   
    <tr> 
    <td>{{{ $row->company_name }}}</td>
    <td>{{{ $row->bu_name }}}</td>
    <td>{{{ $row->bu_site }}}</td>
    <td nowrap='nowrap'><a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.edit')}}" href="/dashboard/company-edit/{{hash('sha256',$salt.$row->company_id)}}/{{$row->company_id}}">
    <span class="glyphicon glyphicon-edit"></span></a>
    </td>
    <td>
    @if($row->bu_id > 1)
    <a href="/dashboard/enter-worksite/{{hash('sha256', $salt.$row->company_id)}}/{{$row->company_id}}">{{Lang::get('mowork.enter')}}</a>
    @endif
    </td>
    </tr>

  @endforeach
 
  @if(count($rows))
     </tbody>
     </table>
  @endif
  
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
