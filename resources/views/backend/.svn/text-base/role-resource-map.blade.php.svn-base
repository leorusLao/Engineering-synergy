@extends('backend-base')
@section('css.append')
    <link rel="StyleSheet" href="/asset/css/jquery.treetable.css" type="text/css" />
    <link rel="StyleSheet" href="/asset/css/jquery.treetable.theme.default.css" type="text/css" />
    <script type="text/javascript" src="/asset/js/jquery.treetable.js"></script>
     
@stop
@section('content')

<div class="col-xs-12 col-sm-12 col-sm-offset-2">

    @if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
   
  <table id="rr" class="treetable" style=" overflow-y:scroll; height:500px;width:400px; display:block;">
  <tr data-tt-id="0">
    <td>{{Lang::get('mowork.role_privilege')}}</td>
  </tr>
  @foreach($rows as $row)
  <tr data-tt-id="{{$row->resource_id}}" data-tt-parent-id="{{$row->parent_id}}">
    <td>{{$row->block1. $row->block2 . $row->block3}}  
    
        <input type="checkbox" class="chkclass" id="cbx{{$row->resource_id}}" value="{{$row->resource_id}}">
       
    </td>
  </tr>
  @endforeach
</table>
  
<script>
$("#rr").treetable({ expandable: true });
$("#rr").treetable('expandAll');
</script>
 
</div>
 
<div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-md" style="top: 3%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.privilege_detail')}}</h4>
			</div>
		<form action="/dashboard/role-resource-map/setup" id="form1" method="post">	
		<div class="modal-body">
		 
		 <div class="table-responsive table-scrollable">
	  
	     <table class="table data-table table-bordered" id="tb1">
     
		 <tbody>
		    <tr>
				<td>{{ Lang::get('mowork.read') }} <input name="read" type="checkbox" value="1" id="read"></td>
				<td>{{ Lang::get('mowork.add_update') }} <input name="write" type="checkbox" value="1" id="write" ></td> 
				<td>{{ Lang::get('mowork.delete') }} <input name="delete" type="checkbox" value="1" id="delete" ></td>
		 	</tr>
              
		 </tbody>
		 </table>
		 </div>
		 </div>
		 <input name="_token" type="hidden" value="{{ csrf_token() }}">
		 <input name="resource_id" type="hidden" value="" id="resource_id">
		 <input name="role_id" type="hidden" value="{{$role->role_id}}">
		 <div class="text-center margin-b20">
           <input type="submit" name="submit" class="btn btn-info" value="{{Lang::get('mowork.confirm')}}" />
           <button id="cancel" class="btn" data-dismiss="modal">{{Lang::get('mowork.cancel')}}</button>
         </div>
		 </form>
   </div>
  </div>
</div>
@if(Session::has('result'))
	<script type="text/javascript">
     $("#cbx{{Session::get('resource_id')}}").prop('checked',true);
	</script>
@endif		 	
 
@stop

@section('footer.append')
<script type="text/javascript">

    $(function(){
    	$(".modal-dialog").draggable({
      	    handle: ".modal-header"
      	});
       
        $('#me8').addClass('active');  

        $('input.chkclass').on('change', function() {
        	 $('#read').prop('checked',false);
             $('#write').prop('checked',false);
             $('#delete').prop('checked',false);
            $('input.chkclass').not(this).prop('checked', false);  
        });

        $('input[type="checkbox"]').on('change', function(e){
               if(e.target.checked){
        	     $('#formholder').modal();
        	   }
        });

        @if(Session::has('result'))
   		 $("#cbx{{Session::get('resource_id')}}").focus()
   	    @endif	
        
        $('#formholder').on('show.bs.modal', function(e) {
            
            checkedbox = '';
             
            $('input:checkbox[id^="cbx"]:checked').each(function(){
            	checkedbox = $(this).attr("id");
            });
             
            resource_id = $('#'+checkedbox).val();
            $('#resource_id').val(resource_id);
            
            //alert('resource=='+ resource_id +'; role_id===' + {{$role->role_id}});           
            
            
            $.ajax({
    	        type:"POST",
    	        url : '{{url("/dashboard/get-role-resource-info")}}',
    	        data : { role_id: {{$role->role_id}}, resource_id: resource_id, _token: "{{csrf_token()}}" },
    	        dataType: 'json',
    	        success : function(result) {
    	         	for(var ii in result){
        	         	 if(ii == 1) {
                             if(result[ii] == 1) {
								$('#read').prop('checked',true);
                             }
            	         } else if(ii == 2) {
                             if(result[ii] == 1) {
                            	$('#write').prop('checked',true);
                             }
            	         } else if(ii == 3) {
                             if(result[ii] == 1) {
                            	 $('#delete').prop('checked',true);
                             }
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

   
</script>
 
@stop