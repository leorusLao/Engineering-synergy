@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
<div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add')}}{{Lang::get('mowork.calendar')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add')}}{{Lang::get('mowork.calendar')}}</span></a>
</div>
<div class="text-center text-danger margin-b20">{{Lang::get('mowork.calendar_note')}}</div>  
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif

 @if(count($rows))
	<div class="table-responsive table-scrollable">
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.cal_code')}}</th>
				<th>{{Lang::get('mowork.cal_name')}}</th>
				<th>{{Lang::get('mowork.action')}}</th>
				<th>{{Lang::get('mowork.make_calendar')}}</th>
				<th>{{Lang::get('mowork.current_month_cal')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif 
			
			@foreach($rows as $row)
 
			<tr>
				<td>{{{ $row->cal_code }}}</td>

				<td>{{{ $row->cal_name }}}</td>
				
				<td>@if($row->company_id)
				    <a href="/dashboard/calendar/edit/{{hash('sha256',$salt.$row->cal_id)}}/{{$row->cal_id}}"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					<a href="/dashboard/calendar/delete/{{hash('sha256',$salt.$row->cal_id)}}/{{$row->cal_id}}"><span class="glyphicon glyphicon-trash"></span></a>
				    @endif
				</td>
				<td> 
					<a href="/dashboard/calendar/make/{{hash('sha256',$salt.$row->cal_id)}}/{{$row->cal_id}}"><span class="glyphicon glyphicon-cog"></span></a>
				</td>
				<td>
				<?php  $cal = App\Models\WorkCalendarReal::where(array('company_id' => $company_id, 'cal_id' => $row->cal_id, 'cal_year' => date('Y') ))->first();
				       $calDone = Lang::get('mowork.not_made');
				       if($cal) {
				       	   $current_month = date('n');
				       	   $month = 'did_month'.$current_month;
				       	   if ($cal->$month) {
				       	      $calDone = Lang::get('mowork.made'); 
				       	   }  
				        }
				       
				?>
				{{$calDone}}
				</td>	 
				
			</tr>

			@endforeach 
			
			@if(count($rows))

		</tbody>

	</table>

	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.calendar')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/calendar/add' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group">
						<input type="text" class="form-control" name="cal_code"
							placeholder="{{Lang::get('mowork.cal_code')}}"
							title="{{Lang::get('mowork.cal_code')}}" id='cal_code'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="cal_name"
							placeholder="{{Lang::get('mowork.cal_name')}}"
							title="{{Lang::get('mowork.cal_name')}}" id='cal_name'>
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


@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	  var errors = '';
    		 
    	  var shift_code = $.trim($('#cal_code').val()); 
    	  if(shift_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.calcode_required')}} \n";	
    		}

    	  var shift_name = $.trim($('#cal_name').val()); 
    	  if(shift_name < 1) {
    	     errors += "{{Lang::get('mowork.calname_required')}} \n";	
    		}
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }
    
</script>


@stop
