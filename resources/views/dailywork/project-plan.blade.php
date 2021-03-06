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
    
    {{ Form::open(array('url' => '/dashboard/project-plan', 'method' => 'post', 'class' => 'form-inline')) }}
       
  		   {{ Form::text('qtext','',array('class' => 'form-control', 'id' => 'qtext' )) }}
  	       {{ Form::submit(Lang::get('mowork.search'),array('name' => 'search','class' => 'btn btn-info','id' =>'sbmtbtn')) }}
        
    {{ Form::close()}}
    
	    	<div class="margin-t20 table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			 
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.customer_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.customer_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
			 
			<th nowrap="nowrap">{{Lang::get('mowork.part_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.date_acceptance')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.calendar')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.plan_type')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_number')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.plan_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.status')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.make_plan')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.pause')}}&#9679;{{Lang::get('mowork.resume')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.complete')}}&#9679;{{Lang::get('mowork.anti_complete')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.comment')}}</th>
			</tr>
			</thead>

			<tbody>
 			<?php $last_proj = 0; ?>
			@foreach($rows as $row)

				<tr>
				 
				@if($row->proj_id != $last_proj)
				<td><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->proj_id)}}/{{$row->proj_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td>{{ $row->customer_id }}</td>
				<td>{{ $row->customer_name }}</td>
				@else
				<td></td>
				<td></td>
				<td></td>
				@endif
				<td  >{{ $row->proj_name }}</td>
				 
				<td  >{{ $row->part_name }}</td>
				<td >{{ $row->start_date }}</td>
				<td ><a href="/dashboard/calendar/make/{{hash('sha256',$salt.$row->cal_id)}}/{{$row->cal_id}}" target="_blank">{{ $row->cal_name }}</a></td>
				<td >
				   <select disabled>
				   @foreach($plantypes as $t)
				     <option @if($t->type_id = $row->plan_type) selected @endif>{{$t->type_name}}</option>
				   @endforeach
				   </select>
				</td>
				<td > 
				<div><a href='#formholder' data-toggle="modal" onclick="setSelectedPJdetail({{$row->id}}, {{$row->plan_id? $row->plan_id:0}})">
				    {{ $row->plan_code? $row->plan_code:''}} 
                     </a>
                </div>
				</td>
				<td ><a href='#formholder' data-toggle="modal" onclick="setSelectedPJdetail({{$row->id}}, {{$row->plan_id? $row->plan_id:0}})">
				     {{ $row->plan_name }}
                     </a>
                </td>
				<td ><?php  
				        if($row->status == 6) echo Lang::get('mowork.pause');
				        elseif($row->status == 10) echo Lang::get('mowork.complete');
				        elseif($row->status == 1) echo Lang::get('mowork.no_plan');
				        elseif($row->status == 2) echo Lang::get('mowork.draft_plan');
				        elseif($row->status == 3) echo Lang::get('mowork.pending');
				        elseif($row->status == 4) echo Lang::get('mowork.normal');
				        elseif($row->status == 5) echo Lang::get('mowork.disagree');
				        elseif($row->status == 7) echo Lang::get('mowork.complete');
				     ?>
			    </td>
				<td >{{ $row->proj_manager}}</td>
				<td class="text-center"> 
				@if($row->plan_id && $row->status != 7)
	            <a href="/dashboard/make-plan/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.make_plan')}}" target="_blank"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
				<a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.view_plan')}}" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a>
				@elseif ( $row->status == 7)
				<a href="/dashboard/view-plan-chart/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.view_plan')}}" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a>
				@endif
				</td>
				<td class="text-center"> 
				@if($row->plan_id && $row->approval_status == 3)
	            <a href="/dashboard/pause-plan/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.pause')}}"  @if($row->status == 4) style="pointer-events: none;" @endif><span class="glyphicon glyphicon-pause"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/dashboard/resume-plan/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.resume')}}"  @if($row->status != 4) style="pointer-events: none;" @endif><span class="glyphicon glyphicon-play"></span></a>
				@endif
				</td>
				<td class="text-center"> 
				@if($row->plan_id && $row->approval_status == 3)
	            <a href="/dashboard/complete-plan/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.complete')}}"><span class="glyphicon glyphicon-off"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/dashboard/anti-complete-plan/{{hash('sha256',$salt.$row->plan_id)}}/{{$row->plan_id}}" title="{{Lang::get('mowork.anti_complete')}}" @if($row->status != 10) style="pointer-events: none;" @endif><span class="glyphicon glyphicon-repeat"></span></a>
				@endif
				</td>
				<td>@if($row->assigned_supplier)
				       {{Lang::get('mowork.outsource_part')}}
				    @endif
				</td>
	 		</tr>
             <?php $last_proj = $row->proj_id ?>
	 		@endforeach
              
	 		</tbody>

	 		</table>

	 		<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>
 
 
<div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-md" style="top: 1%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.plan_master')}}</h4>
			</div>
			
		<div class="modal-body">
		  <div class="text-left">{{Lang::get('mowork.basic_info')}}</div>
		  
		  <form action="/dashboard/project-plan" id="form1" method="post" onsubmit="return validatePlanMaster()">
		  <div class="table-responsive table-scrollable">
	  
	     <table class="table data-table table-bordered" id="tb1">
     
		 <tbody>
		    <tr>
				<td>{{ Lang::get('mowork.plan_code') }}: <input type="text" name="plan_code" id="plan_code" value=""></td>
				<td>{{ Lang::get('mowork.plan_name') }}: <input type="text" name="plan_name" id="plan_name" value=""></td> 
		 	</tr>
           
            <tr>
				<td>{{ Lang::get('mowork.plan_type') }}: <select name="plan_type" id="plan_type">
				                                         <option></option>
				                                         @foreach($plantypes as $type)
				                                         <option value="{{$type->type_id}}">{{$type->type_name}}</option>
				                                         @endforeach
				                                         </select> </td>
				<td>{{ Lang::get('mowork.plan_description') }}: <textarea name="plan_des" id="plan_des"></textarea></td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.leader') }}: <select name="leader" id="leader">
				                                       <option></option>
				                                       @foreach($employees as $man)
				                                         <option value="{{$man->uid}}">{{$man->fullname}}</option>
				                                       @endforeach
				                                      </select></td>
				<td>{{ Lang::get('mowork.plan_member') }}:   <select class="margin-t20" id='pickman' multiple="multiple" name="people[]">
			                                             @foreach ($employees as $man)
			                                            <option value="{{$man->uid}}" id="man{{$man->uid}}">{{$man->fullname}}</option>
			                                             @endforeach
		                                                 </select></td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.start_date') }}:
                 <div class='input-group date' id='datepicker1'>
                 <input type="text" name="start_date" id="start_date" class="form-control">
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
				<td>{{ Lang::get('mowork.end_date') }}: 
				 <div class='input-group date' id='datepicker2'>
                 <input type="text" name="end_date" id="end_date" class="form-control">
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
		  
		</tbody>

	   </table>
	    	</div>
   <input name="_token" type="hidden" value="{{ csrf_token() }}">
   <div>
    <input name="plan_id" type="hidden" value="" id="plan_id">
    <input name="proj_detail_id" type="hidden" value="" id="proj_detail_id">
    <input type="submit" name="submit" class="btn btn-info" value="{{Lang::get('mowork.confirm')}}" />
    <button id="cancel" class="btn" data-dismiss="modal">{{Lang::get('mowork.cancel')}}</button>
</div>
</form>			
			
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
    	    	$(".modal-dialog").draggable({
    	    	    handle: ".modal-header"
    	    	});
    	    	
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
    	  	     $('#pickman').multiselect({
    	        	numberDisplayed: 20,
    	        	allSelectedText: '{{Lang::get("mowork.selected_all")}}',
    	        	nonSelectedText: '{{Lang::get("mowork.please_select")}}',
    	            nSelectedText: '{{Lang::get("mowork.click_see")}}'
    	        });
    	  	    


        
    	$('input.input_check').on('change', function() {
    	    $('input.input_check').not(this).prop('checked', false);  
    	});
    	 
        $('#me8').addClass('active');  

        $('#formholder').on('show.bs.modal', function(e) {
        	 
        	proj_detail_id = $('#proj_detail_id').val();
        	plan_id = $('#plan_id').val();
                          
            $.ajax({
	        type:"POST",
	        url : '{{url("/dashboard/edit-plan-master")}}',
	        data : { 
		            proj_detail_id: proj_detail_id,
		            plan_id: plan_id,
		            _token: "{{csrf_token()}}",
		            submit: "submit" },
	        dataType: 'json',
	        success : function(result) {
                //alert(JSON.stringify(result));
	         	for(var ii in result){
		         
    	           if(ii == 1) {
                       $('#plan_code').val(result[1] == null ? '': result[1]);
        	       } else if(ii == 2) {
                       $('#plan_name').val(result[2] == null ? '': result[2]);
        	       } else if(ii == 3 ) {
        	    	   $('#plan_type').val(result[3] == null ? '': result[3]);
            	   } else if(ii == 4 ) {
        	    	   $('#plan_des').val(result[4] == null ? '': result[4]);
            	   } else if(ii == 5 ) {
        	    	   $('#leader').val(result[5] == null ? '': result[5]);
            	   } else if(ii == 6) {
						var array = result[6].split(',');
						$('#pickman').multiselect('select', array);
    	           } else if(ii == 7 ) {
        	    	   $('#start_date').val(result[7] == null ? '': result[7]);
            	   } else if(ii == 8 ) {
        	    	   $('#end_date').val(result[8] == null ? '': result[8]);
            	   } else if(ii == 9) {
            		   $('#plan_id').val(result[9] == null ? '': result[9]);
            		    
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

    function setSelectedPJdetail(id, plan_id) {
           $('#proj_detail_id').val(id);
           $('#plan_id').val(plan_id);
         
    }
</script>


@stop