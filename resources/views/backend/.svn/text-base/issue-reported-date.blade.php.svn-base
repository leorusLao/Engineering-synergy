@extends('backend-base')

@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3">

@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
	<form action='/dashboard/issue-scan/reported-date' method='post'
			autocomplete='off' role='form' id="form" onsubmit='return validateForm();'>
			<div class="table-responsive table-scrollable">
			<table class="table data-table table-bordered">

			<tbody>
			<tr>
			<td>{{ Lang::get('mowork.scan_days') }}</td>
			<td>{{ Lang::get('mowork.before') }} <input type="text" name="date_range" value="{{$row->date_range}}"  disabled id='date_range'> {{ Lang::get('mowork.days') }}</td>
			</tr>
			<tr>
			<td>{{Lang::get('mowork.message_via')}}</td>
			<td>&nbsp;&nbsp;{{Lang::get('mowork.site_message')}}<input type="checkbox" name="message_via[]" value='site_message'disabled @if(isset($row) && strstr($row->trigger_event,'site_message')) checked @endif>
			&nbsp;&nbsp;{{Lang::get('mowork.small_routine')}}<input type="checkbox" name="message_via[]" value='small_routine' disabled @if(isset($row) && strstr($row->trigger_event,'small_routine')) checked @endif>
			&nbsp;&nbsp;{{Lang::get('mowork.sms')}}<input type="checkbox" name="message_via[]" value='sms' disabled @if(isset($row) && strstr($row->trigger_event,'sms')) checked @endif>
			&nbsp;&nbsp;{{Lang::get('mowork.email')}}<input type="checkbox" name="message_via[]" value='email' disabled @if(isset($row) && strstr($row->trigger_event,'email')) checked @endif>
			</td>
			</tr>
			<tr>
			<td>
			{{Lang::get('mowork.advised_people')}}

			</td>
			<td><div id="people">
			<?php if(isset($row)) {
				$people = explode(',', $row->people_list);
			}
			?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="people[]" value="{{$val->uid}}" disabled @if(isset($people) && in_array($val->uid, $people)) checked @endif >{{$val->fullname}} {{$val->name}}<br>
				 		@endforeach
				 		</div>
				 </td>
		 	</tr>
		 	 
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