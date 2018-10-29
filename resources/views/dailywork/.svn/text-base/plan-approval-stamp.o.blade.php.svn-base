@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
 
@stop
 
@section('content')
<div class="col-xs-12 col-sm-12">

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		    
		    <div class="table-responsive table-scrollable">
		    <table class="table data-table table-bordered" id="tb1">
         <tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}: {{$binfo->proj_code}}</td>
				<td>{{ Lang::get('mowork.project_name') }}: {{$binfo->proj_name}}</td> 
				<td>{{ Lang::get('mowork.project_type') }}: {{$binfo->proj_type}}</td> 
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.project_manager') }}: {{$binfo->proj_name}}</td> 
				<td>{{ Lang::get('mowork.customer_number') }}: {{$binfo->proj_name}}</td>
				<td>{{ Lang::get('mowork.customer_name') }}: {{$binfo->proj_name}}</td> 
		 	</tr>
           <tr>
				<td>{{ Lang::get('mowork.plan_type') }}: {{$binfo->proj_name}}</td> 
				<td>{{ Lang::get('mowork.plan_code') }}: {{$binfo->proj_name}}</td>
				<td>{{ Lang::get('mowork.plan_name') }}: {{$binfo->proj_name}}</td> 
		 	</tr>
		 	 
		</tbody>
		 

	       </table>
	<div>{{Lang::get('mowork.node_info')}}</div>
	 
		    
		    <form action='/dashboard/plan-approval/stamp/{{$token}}/{{$plan_id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="text-align: center;"><input type="checkbox" class="check_all" /></th>
						<th>{{Lang::get('mowork.node_code')}}</th>
						<th>{{Lang::get('mowork.node_name')}}</th>
						<th>{{Lang::get('mowork.department')}}</th>
						<th>{{Lang::get('mowork.duration')}}</th>
						<th>{{Lang::get('mowork.workdays')}}</th>
						<th>{{Lang::get('mowork.start_date')}}</th>
						<th>{{Lang::get('mowork.end_date')}}</th>
				 		<th>{{Lang::get('mowork.approval')}}</th>
						 
					</tr>
				</thead>
				<tbody>
  
					@foreach ($rows as $row)
				 
					<tr>
						<td style="text-align: center;">
						   @if($row->status == 0)<input type="checkbox" class="input_check" name="cbx[]" value="{{$row->task_id}}"/>
						   @endif
						</td>
						<td>{{$row->node_no}}</td>
						<td><a href="">{{$row->name}}</a></td>
						<td>{{$row->department}}</td>
						<td>{{$row->duration}}</td>
						<td>{{$row->workdays}}</td>
						<td>{{substr($row->start_date,0,10)}}</td>
						<td>{{substr($row->end_date,0,10)}}</td>
						<td>{{$row->status > 0 ? ($row->status == 1? Lang::get('mowork.agree'): Lang::get('mowork.disagreed')):(Lang::get('mowork.unapproval'))}}</td>
					</tr>
				    @endforeach
				</tbody>
			</table>
			</div>
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			 
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group input-group">
              <div class="input-group-addon">{{Lang::get('mowork.approval_comment')}}</div>
               <textarea class="form-control" name="comment" id="biz_des" rows="2"></textarea>
            </div>
			 
			<div class="clearfix"></div>
			<div class="text-center"><input type="submit" class="btn btn-info" name="submit" value="{{Lang::get('mowork.agree')}}"> <input type="submit" class="btn btn-info" name="submit" value="{{Lang::get('mowork.disagree')}}"> <p class="btn btn-info" onclick="window.top.close()">{{Lang::get('mowork.cancel')}}</p></div>
			</form>
			</div>
			
			<div class='text-center'><?php echo $rows->links(); ?></div>
		</div>
	</div>
</div>
 
</div>

@stop

@section('footer.append')
<script>	

$(function(){ 
 
	$('.check_all').bind('click',cheak_all);
	 
	function cheak_all(){ 
		if($('.check_all').is(':checked') == true){ 
			$('.input_check').prop('checked',true);
		}else{ 
			$('.input_check').prop('checked',false);
		}
	}
 
})
 
  function validateForm(){
      var errors = '';
    	 
      $cbx = $("input:checkbox[name='cbx[]']"); 
      if(! $cbx.is(":checked") ){
      	  errors = "{{Lang::get('mowork.check_node_required')}}\n";
      }  
       
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
       
      return true;
      
    }
  
</script>
@stop