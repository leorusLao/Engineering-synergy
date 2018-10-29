@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
   <form action='/dashboard/plan-scan/plan-start' method='post'
					autocomplete='off' role='form' id="form" onsubmit='return validateForm();'>
    <div class="table-responsive table-scrollable">
	<table class="table">
 
		<tbody>
           @foreach($rows as $row) 
            <tr>
				<td>
				   @if($row->level == 1)
				     {{$row->block1}}
				   @endif
				</td>
				<td>
				   @if($row->level == 2)
				     {{$row->block2}}
				   @endif
				</td>
				<td> 
				    @if($row->level == 3)
				     {{$row->block3}}
				   @endif
				</td>
		 	</tr>
            
	 	   @endforeach 
		</tbody>

	</table>
	<input name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class="btn btn-info text-center" onclick="activeEdit()" id='edit' type="submit">{{Lang::get('mowork.edit')}}</div>
	<input class="btn btn-info text-center" name="submit" type="submit"  value="{{Lang::get('mowork.save')}}"  id="submit" style="display:none">
    </div>
    </form>
    
	<div class="clearfix"></div>

</div>

@stop 

@section('footer.append')
  
<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	 
        $('#me8').addClass('active');   
 
    });


    function validateForm() {
      var errors = '';

 	  date_range = $('#date_range').val();
 	  if( Math.floor(date_range) != date_range ||  ! $.isNumeric(date_range ) || date_range < 1 ) {
 		 errors += "{{Lang::get('mowork.dealydays_required')}}\n";
 	  }
 	  
      $cbx_via = $("input:checkbox[name='message_via[]']"); 
      if(! $cbx_via.is(":checked") ){
      	  errors += "{{Lang::get('mowork.message_via_required')}}\n";
      }  

      $cbx_task = $("input:checkbox[name='people[]']"); 
      if(! $cbx_task.is(":checked") ){
      	  errors += "{{Lang::get('mowork.people_required')}}\n";
      } 
          
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
      return true;
      
    }

    function activeEdit() {
     	 $("#form :input").prop("disabled", false);
     	 $('#edit').css('display','none');
    	 $('#submit').css('display','block');
    }
</script>


@stop