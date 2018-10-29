@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
   <form action='/dashboard/plan-scan/plan-completion-alert' method='post'
					autocomplete='off' role='form' id="form" onsubmit='return validateForm();'>
    <div class="table-responsive table-scrollable">
	
	<div>{{Lang::get('mowork.first_grade')}}</div>
	<table class="table data-table table-bordered" id="tb1">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.name') }}</td>
				<td><input type="text" name="scan_name1" value="{{$row1->scan_name}}"  disabled id='name1'></td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.advanced_alert') }}</td>
				<td>{{ Lang::get('mowork.before') }} <input type="text" name="date_range1" value="{{$row1->date_range}}"  disabled id='date_range1'> {{ Lang::get('mowork.days') }}</td>
		 	</tr>
            <tr>
				<td>{{Lang::get('mowork.message_via')}}</td>
				<td>&nbsp;&nbsp;{{Lang::get('mowork.site_message')}}<input type="checkbox" name="message_via1[]" value='site_message' disabled @if(isset($row1) && strstr($row1->trigger_event,'site_message')) checked @endif> 				 
						&nbsp;&nbsp;{{Lang::get('mowork.small_routine')}}<input type="checkbox" name="message_via1[]" value='small_routine' disabled @if(isset($row1) && strstr($row1->trigger_event,'small_routine')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.sms')}}<input type="checkbox" name="message_via1[]" value='sms' disabled @if(isset($row1) && strstr($row1->trigger_event,'sms')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.email')}}<input type="checkbox" name="message_via1[]" value='email' disabled @if(isset($row1) && strstr($row1->trigger_event,'email')) checked @endif>
	            </td>
		 	</tr>
		 	<tr>
				<td>
						{{Lang::get('mowork.advised_people')}}
					 
				</td>
				<td><div id="people1">
						<?php if(isset($row1)) {
						         $people = explode(',', $row1->people_list);
						      }
						?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="people1[]" value="{{$val->uid}}" disabled  @if(isset($people) && in_array($val->uid, $people)) checked @endif >{{$val->fullname}} {{$val->name}}<br>
				 		@endforeach
				 		</div>
				 </td>
		 	</tr>
		 	 
		</tbody>

	</table>
	
	<div>{{Lang::get('mowork.second_grade')}} <span class="margin-l20"><input type="checkbox" value="1" name="enable2" @if(isset($row2) && $row2->is_active) checked @endif id="enable2">{{Lang::get('mowork.enable')}} </span></div>
	<table class="table data-table table-bordered" id="tb2">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.name') }}</td>
				<td><input type="text" name="scan_name2" value="{{$row2->scan_name}}"  disabled id='name2'></td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.advanced_alert') }}</td>
				<td>{{ Lang::get('mowork.before') }} <input type="text" name="date_range2" value="{{$row2->date_range}}"  disabled id='date_range2'> {{ Lang::get('mowork.days') }}</td>
		 	</tr>
            <tr>
				<td>{{Lang::get('mowork.message_via')}}</td>
				<td>&nbsp;&nbsp;{{Lang::get('mowork.site_message')}}<input type="checkbox" name="message_via2[]" value='site_message' disabled @if(isset($row2) && strstr($row2->trigger_event,'site_message')) checked @endif> 				 
						&nbsp;&nbsp;{{Lang::get('mowork.small_routine')}}<input type="checkbox" name="message_via2[]" value='small_routine' disabled @if(isset($row2) && strstr($row2->trigger_event,'small_routine')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.sms')}}<input type="checkbox" name="message_via2[]" value='sms' disabled @if(isset($row2) && strstr($row2->trigger_event,'sms')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.email')}}<input type="checkbox" name="message_via2[]" value='email' disabled @if(isset($row2) && strstr($row2->trigger_event,'email')) checked @endif>
	            </td>
		 	</tr>
		 	<tr>
				<td>
						{{Lang::get('mowork.advised_people')}}
					 
				</td>
				<td><div id="people">
						<?php if(isset($row2)) {
						         $people = explode(',', $row2->people_list);
						      }
						?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="people2[]" value="{{$val->uid}}" disabled  @if(isset($people) && in_array($val->uid, $people)) checked @endif >{{$val->fullname}} {{$val->name}}<br>
				 		@endforeach
				 		</div>
				 </td>
		 	</tr>
		 	 
		</tbody>

	</table>
	
	<div>{{Lang::get('mowork.third_grade')}} <span class="margin-l20"><input type="checkbox" value="1" name="enable3"  @if(isset($row3) && $row3->is_active) checked @endif id="enable3">{{Lang::get('mowork.enable')}} </span></div>
	<table class="table data-table table-bordered" id="tb3">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.name') }}</td>
				<td><input type="text" name="scan_name3" value="{{$row3->scan_name}}"  disabled id='name3'></td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.advanced_alert') }}</td>
				<td>{{ Lang::get('mowork.before') }} <input type="text" name="date_range3" value="{{$row3->date_range}}"  disabled id='date_range3'> {{ Lang::get('mowork.days') }}</td>
		 	</tr>
            <tr>
				<td>{{Lang::get('mowork.message_via')}}</td>
				<td>&nbsp;&nbsp;{{Lang::get('mowork.site_message')}}<input type="checkbox" name="message_via3[]" value='site_message' disabled @if(isset($row3) && strstr($row3->trigger_event,'site_message')) checked @endif> 				 
						&nbsp;&nbsp;{{Lang::get('mowork.small_routine')}}<input type="checkbox" name="message_via3[]" value='small_routine' disabled @if(isset($row3) && strstr($row3->trigger_event,'small_routine')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.sms')}}<input type="checkbox" name="message_via3[]" value='sms' disabled @if(isset($row3) && strstr($row3->trigger_event,'sms')) checked @endif>
						&nbsp;&nbsp;{{Lang::get('mowork.email')}}<input type="checkbox" name="message_via3[]" value='email' disabled @if(isset($row3) && strstr($row3->trigger_event,'email')) checked @endif>
	            </td>
		 	</tr>
		 	<tr>
				<td>
						{{Lang::get('mowork.advised_people')}}
					 
				</td>
				<td><div id="people">
						<?php if(isset($row2)) {
						         $people = explode(',', $row3->people_list);
						      }
						?>
				 		@foreach($employees as $val)
				 		<input type="checkbox"  name="people3[]" value="{{$val->uid}}" disabled  @if(isset($people) && in_array($val->uid, $people)) checked @endif >{{$val->fullname}} {{$val->name}}<br>
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

 	  date_range = $('#date_range1').val();
 	  if( Math.floor(date_range) != date_range ||  ! $.isNumeric(date_range ) || date_range < 1 ) {
 		 errors += "{{Lang::get('mowork.dealydays_required')}}\n";
 	  }
 	  
      $cbx_via = $("#tb1 input:checkbox[name='message_via1[]']"); 
      if(! $cbx_via.is(":checked") ){
      	  errors += "{{Lang::get('mowork.first_grade')}}{{Lang::get('mowork.message_via_required')}}\n";
      }  

      $cbx_task = $("#tb1 input:checkbox[name='people1[]']"); 
      if(! $cbx_task.is(":checked") ){
      	  errors += "{{Lang::get('mowork.first_grade')}}{{Lang::get('mowork.people_required')}}\n";
      } 

      if($('#enable2').prop('checked')) {
          name2 = $.trim($('#name2').val());
          if(name2.length < 1 ){
          	  errors += "{{Lang::get('mowork.second_grade')}}{{Lang::get('mowork.name_required')}}\n";
          } 
          
    	  $cbx_via2 = $("#tb2 input:checkbox[name='message_via2[]']"); 
          if(! $cbx_via2.is(":checked") ){
          	  errors += "{{Lang::get('mowork.second_grade')}}{{Lang::get('mowork.message_via_required')}}\n";
          }  

          $cbx_task2 = $("#tb2 input:checkbox[name='people2[]']"); 
          if(! $cbx_task2.is(":checked") ){
          	  errors += "{{Lang::get('mowork.second_grade')}}{{Lang::get('mowork.people_required')}}\n";
          } 
      } 

      if($('#enable3').prop('checked')) {
          name3 = $.trim($('#name3').val());
          if(name3.length < 1 ){
          	  errors += "{{Lang::get('mowork.third_grade')}}{{Lang::get('mowork.name_required')}}\n";
          } 
          
    	  $cbx_via3 = $("#tb3 input:checkbox[name='message_via3[]']"); 
          if(! $cbx_via3.is(":checked") ){
          	  errors += "{{Lang::get('mowork.third_grade')}}{{Lang::get('mowork.message_via_required')}}\n";
          }  

          $cbx_task3 = $("#tb3 input:checkbox[name='people3[]']"); 
          if(! $cbx_task3.is(":checked") ){
          	  errors += "{{Lang::get('mowork.third_grade')}}{{Lang::get('mowork.people_required')}}\n";
          } 
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