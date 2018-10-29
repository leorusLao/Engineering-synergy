@extends('backend-base')
@section('css.append')
<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
@stop
@section('content')

<div class="col-xs-12">

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
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
		    <th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
		 
			<th nowrap="nowrap">{{Lang::get('mowork.plan_number')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.node_code')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.node_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.department')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.task_status')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.approval')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_start')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_completion')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.real_start')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.real_completion')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.make_plan')}}</th>
            </tr>
			</thead>

			<tbody>
 			<?php $last_plan_id = 0;?>
			@foreach($rows as $row)

				<tr>
				@if($row->plan_id == $last_plan_id)
				<td></td>
				<td></td>
				 
				<td></td>
				<td></td>
				@else
				<td  ><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->project_id)}}/{{$row->project_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td  >{{ $row->proj_name }}</td>
				 
				<td  ><a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" target="_blank">{{ $row->plan_code }}</a></td>
				<td  >{{ $row->plan_name }}</td>
				@endif
				<td >{{ $row->node_no  }}</td>
			 
				<td >{{ $row->name }}</td>
				<td >{{ $row->dep_name }}</td>
				<td >{{ $row->fullname }}</td>
				<td ><a href="/dashboard/plan-task-detail/view/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}" target="_blank">
				      @if ($row->process_status == 0) {{Lang::get('mowork.unprocessed')}}
				      @elseif ($row->process_status == 1) {{Lang::get('mowork.accepted')}}
				      @elseif ($row->process_status == 2) {{Lang::get('mowork.unaccepted')}}
				      @elseif ($row->process_status == 3) {{Lang::get('mowork.processing')}}
				      @elseif ($row->process_status == 10) {{Lang::get('mowork.completed')}}
				      @endif
				       
				    </a>
				</td>
				<td ><?php  if($row->status == 0) echo Lang::get('mowork.pending');
				        elseif($row->status == 1) echo Lang::get('mowork.agree');
				        elseif($row->status == 2) echo Lang::get('mowork.disagree');
				     ?>
			    </td>
				<td >{{ substr($row->start_date,0,10) }}</td>
				<td nowrap="nowrap">{{ substr($row->end_date,0,10) }}</td>
				<td nowrap="nowrap">{{ $row->real_start }}</td>
                <td nowrap="nowrap">{{ $row->real_end }}</td>
                <td class="text-center">
                @if($row->dep_id)
                <a href="/dashboard/department-plan/make/{{hash('sha256',$salt.$row->task_id)}}/{{$row->task_id}}"><span class="glyphicon glyphicon-edit"></span></a>
                @endif
                </td>
 	 		</tr>
             <?php $last_plan_id = $row->plan_id; ?>
	 		@endforeach

	 		</tbody>

	 		</table>

	 		<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>
 
<div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-lg" style="top: 1%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.progress_input')}}</h4>
			</div>
			
		<div class="modal-body">
		  <div class="text-left">{{Lang::get('mowork.basic_info')}}</div>
		  <div class="table-responsive table-scrollable">
	  
	     <table class="table data-table table-bordered" id="tb1">
     
		 <tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}: <span id="js1"></span></td>
				<td>{{ Lang::get('mowork.project_name') }}: <span id="js2"></span></td> 
		 	</tr>
           
            <tr>
				<td>{{ Lang::get('mowork.project_manager') }}: <span id="js3"></span></td>
				<td>{{ Lang::get('mowork.project_member') }}: <span id="js4"></span></td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.plan_code') }}: <span id="js5"></span></td>
				<td>{{ Lang::get('mowork.plan_name') }}: <span id="js6"></span></td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.node_code') }}: <span id="js7"></span></td>
				<td>{{ Lang::get('mowork.node_name') }}: <span id="js8"></span></td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.task_status') }}: <span id="js9"></span></td> 
		 	 	<td>{{ Lang::get('mowork.approval') }}: <span id="js10"></span></td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.department') }}: <span id="js11"></span></td> 
				<td>{{ Lang::get('mowork.leader') }}: <span id="js12"></span></td> 
		 	</tr> 
		 	<tr>
				<td>{{ Lang::get('mowork.plan_start') }}: <span id="js13"></span></td> 
				<td>{{ Lang::get('mowork.plan_completion') }}: <span id="js14"></span></td> 
		 	</tr> 
		</tbody>

	   </table>
	    
	    <div class="text-left">{{Lang::get('mowork.input_info')}}</div>
		 	
			
				<form action='/dashboard/progress-feed-in' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
				 	<table class="table data-table table-bordered" id="tb1">
					<tbody>
					<tr><td>{{Lang::get('mowork.real_start').Lang::get('mowork.date')}}:
					    
					     <div class='input-group date' id='datepicker1'>
                          <input type="text" name="real_start" id="js15" class="form-control">
                          <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                         </div>
					      <script type="text/javascript">
           					 $(function () {
               				   $('#datepicker1').datetimepicker({
               				     minView: 2,
               					 format: "yyyy-mm-dd",
               			       });
           					 });
           					  
        				  </script>
					    
					    </td>
					    <td>{{Lang::get('mowork.real_completion').Lang::get('mowork.date')}}:
					     <div class='input-group date' id='datepicker2'>
                          <input type="text" name="real_end" id="js16" class="form-control">
                          <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                         </div>
					      <script type="text/javascript">
           					 $(function () {
               				 $('#datepicker2').datetimepicker({
               				     minView: 2,
               					 format: "yyyy-mm-dd",
               			       });
           					 });
        				  </script>
					    </td>
					</tr>
					<tr><td>{{Lang::get('mowork.progress_quote')}}(%)： <input type="text" name="complete" id="js17"></td>
					    <td>{{Lang::get('mowork.duration')}}： <input type="text" id="js18" readonly></td>
					</tr>
					<tr><td colspan="2"><label style="vertical-align: top">{{Lang::get('mowork.progress_remark')}}：</label> <textarea name="progress_remark"  rows="2" cols="60"></textarea></td></tr>
					<tr><td colspan="2">{{Lang::get('mowork.advised_people')}}: 
					     
					    <select class="margin-t20" id='pickman' multiple="multiple" name="people[]">
					     @foreach ($employees as $man)
					      <option value="{{$man->uid}}" id="man{{$man->uid}}">{{$man->fullname}}</option>
					     @endforeach
					    </select>
					     
					    </td>
					</tr>
					<tr><td colspan="2">{{Lang::get('mowork.message_via')}}: 
					                   <input type="checkbox" name="msg_via[]" value="site_message" id="js20">{{Lang::get('mowork.site_message')}}  
					                   &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="msg_via[]" value="small_routine" id="js21">{{Lang::get('mowork.small_routine')}}
					                   &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="msg_via[]" value="sms" id="js22">{{Lang::get('mowork.sms')}}
					                   &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="msg_via[]" value="email" id="js23">{{Lang::get('mowork.email')}}
					     </td>
					</tbody>
					</table>
					<div>
						<input type="submit" class="btn-info" name="submit"
							value="{{Lang::get('mowork.confirm')}}">
					</div>
					<input type="hidden" name="task_id" id="task_id" value="">
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
</div> 
 
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="/asset/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="/asset/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">

    $(function(){
    	$("#datepicker1").on("show", function(e){
    	     e.preventDefault();
    	     e.stopPropagation();
    	}).on("hide", function(e){
    	     e.preventDefault();
    	     e.stopPropagation();
    	});

    	$("#datepicker2").on("show", function(e){
   	     e.preventDefault();
   	     e.stopPropagation();
   	    }).on("hide", function(e){
   	     e.preventDefault();
   	     e.stopPropagation();
   	    });

   	    //above prevent datepicker from fireing 'show.bs.modal';avoid conflict with Datepicker show.bs.modal
        $('#me8').addClass('active');  
        $('#pickman').multiselect({
        	numberDisplayed: 20,
        	allSelectedText: '{{Lang::get("mowork.selected_all")}}',
        	nonSelectedText: '{{Lang::get("mowork.please_select")}}',
            nSelectedText: '{{Lang::get("mowork.click_see")}}'
        });
        
        $('#formholder').on('show.bs.modal', function(e) {
            var bookId = $(e.relatedTarget).data('book-id');
             
            $(e.currentTarget).find('input[name="task_id"]').val(bookId);
            $.ajax({
    	        type:"POST",
    	        url : '{{url("/dashboard/get-plan-node-info")}}',
    	        data : { task_id: bookId, _token: "{{csrf_token()}}" },
    	        dataType: 'json',
    	        success : function(result) {
    	         	for(var ii in result){
        	         	if(ii < 15)
    	        		   $('#js'+ii).text(result[ii] == null ? '':result[ii] );
        	         	else if(ii < 19 && ii >= 15){
                              
         	         	     $('#js'+ii).val( (result[ii] == null || result[ii] == 0) ? '':result[ii] );
        	         	}
        	         	else if(ii == 19) {
							var array = result[19].split(',');
								$('#pickman').multiselect('select', array);
		    	        }
           	         	else {//checkbox > 19
            	         	if(result[ii] == 1) {
        	         		   $('#js'+ii).prop('checked', true);
            	         	}
            	        }	
    	        	}
 
    	        },
    	        error: function() {
    	            //do logic for error
    	        }
    	    });
            
        });
        
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm() {
        var errors = '';

        date1 = $('#js15').val();
        date2 = $('#js16').val();

        if(! isValidDate(date1)) {
        	errors += "{{Lang::get('mowork.realstart_required')}}\n";
        }

        percent = $('#js17').val();
        if( $.trim(date2).length == 0) {
   	       if( ! $.isNumeric(percent) ) {
   		     errors += "{{Lang::get('mowork.progress_required')}}\n";
   	       }
        } else {
        	if(! isValidDate(date2)) {
            	errors += "{{Lang::get('mowork.realend_required')}}\n";
            }
        }
   	    
        $cbx_via = $("input:checkbox[name='msg_via[]']"); 
        if(! $cbx_via.is(":checked") ){
        	  errors += "{{Lang::get('mowork.message_via_required')}}\n";
        }  

        
        if(errors.length > 0) {
      	alert(errors);
    	return false;
        }
        return true;
        
      }


    function isValidDate(dateString)
    {
    	 
      // First check for the pattern
      var regex_date = /^\d{4}\-\d{1,2}\-\d{1,2}$/;

      if(!regex_date.test(dateString))
      {
          return false;
      }

      // Parse the date parts to integers
      var parts   = dateString.split("-");
      var day     = parseInt(parts[2], 10);
      var month   = parseInt(parts[1], 10);
      var year    = parseInt(parts[0], 10);

      // Check the ranges of month and year
      if(year < 1000 || year > 3000 || month == 0 || month > 12)
      {
          return false;
      }
   	  return true;
     }
</script>
 
@stop