@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">

	<ul class="nav nav-justified margin-b30">
		<li><a href="/dashboard/calendar">{{Lang::get('mowork.calendar')}}{{Lang::get('mowork.name')}}</a></li>
		<li><a href="/dashboard/calendar/work-shift">{{Lang::get('mowork.work_shift')}}</a></li>
		<li class='active'><a href="/dashboard/calendar/workday-setup">{{Lang::get('mowork.workday_setup')}}</a></li>

	</ul>

	@if(Session::has('result'))
	<div class="text-danger text-center">{{Session::get('result')}}</div>
	@elseif (isset($restult))
	<div class="text-danger text-center">{{$result}}</div>
	@endif
	 
	
   
    <div class="col-sm-6">
    <form action="/dashboard/calendar/workday-setup" method="post" id="form1" name='form1'>
       
    <select id='year' name='year' class="form-control" style="width:80px">
     @foreach ($yearList as $op => $val)
       <option value="{{$val}}" @if($val == $selectedY) selected @endif>
       {{$val}}
       </option>
     @endforeach
    </select>
 
    <button class="btn margin-t5" id="previous" title="{{Lang::get('mowork.previous_month')}}">
    <span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn margin-t5"  id="next" title="{{Lang::get('mowork.next_month')}}">
    <span class="glyphicon glyphicon-chevron-right"></span></button>
     <input name="_token" type="hidden" value="{{ csrf_token() }}">
     <input name="month" id="month" type="hidden">
     
    </form> 
     
    </div>
    <div class="col-sm-6">
    <div class="text-warning">1. {{Lang::get('mowork.day_warn1')}}</div>
	<div class="text-warning">2. {{Lang::get('mowork.day_warn2')}}</div>
	</div>
    <div class="clearfix"></div>
    
    <div><h3 class="text-center marging-b20"><span id='cyear'>{{$rows[0]->cal_year}}</span><span>.</span><span id='cmonth'>{{$rows[0]->cal_month}}</span></h3></div>
	 
	{{ Form::open(array('url' => '/dashboard/calendar/workday-setup', 'method' => 'post', 'class' => '', 'onsubmit' => 'return validateCheckbox()')) }}
	 
	<div class="table-responsive table-scrollable">
    <table class="table dataTable table-striped display table-bordered table-condensed">
		<thead>
			<tr>
				<th>{{Lang::get('mowork.sund')}}</th>
				<th>{{Lang::get('mowork.mon')}}</th>
				<th>{{Lang::get('mowork.tue')}}</th>
				<th>{{Lang::get('mowork.wed')}}</th>
				<th>{{Lang::get('mowork.thu')}} </th>
				<th>{{Lang::get('mowork.fri')}}</th>
				<th>{{Lang::get('mowork.sat')}}</th>
			</tr>
		</thead>

		<tbody>
 	   
	<?php $startSpace = 1; $spaces = $spacesFirstLine = $rows[0]->dow - 1;  ?>
	
	@if($spaces > 0)
    <tr style="height: 60px">
    @endif
    
    @for($kk = 0; $kk <  $spacesFirstLine; $kk++)
    <td class="">&nbsp;</td>
    @endfor
    
	@foreach($rows as $row)
	      
		@if($spaces % 7 == 0)	
			<tr style="height: 70px">
		@endif   
           @if($row->dow == 1 || $row->dow == 7)           
           <td style="background:#ccc;cursor:pointer" onclick="changeWorkday({{$row->cal_day}}, {{$row->is_workday}})">{{$row->cal_day}}<br>
              @if($row->is_workday)
               <span style="color:#fff" id="id{{$row->cal_day}}" >{{Lang::get('mowork.work')}}</span>
               <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$row->is_workday}}">
              @else
               <span style="color:#fff" id="id{{$row->cal_day}}" ><b>{{Lang::get('mowork.dayoff')}}</b></span>
               <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$row->is_workday}}">
              @endif
           @else
            <td onclick="changeWorkday({{$row->cal_day}}, {{$row->is_workday}})" style="cursor:pointer">{{$row->cal_day}}<br>
               @if($row->is_workday)
               <span id="id{{$row->cal_day}}">{{Lang::get('mowork.work')}}</span>
               <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$row->is_workday}}">
               @else
               <span id="id{{$row->cal_day}}">{{Lang::get('mowork.dayoff')}}</span>
               <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$row->is_workday}}">
               @endif
           @endif
           </td>
           <?php $spaces++; ?>
                   
        @if($spaces % 7 == 0)    
			</tr>
		@endif
           
	@endforeach 
	 	</tbody>
    </table>
	<input type="hidden" name="year" value="{{$selectedY}}">
	<input type="hidden" name="month" value="{{$selectedM}}">
	<div>
	<input class="btn btn-info" type="submit" name="submit" value="{{Lang::get('mowork.save')}}">
	</div>
	{{Form::close()}}
	 
   </div>
  
   <div class="clearfix"></div>
	 
 
</div>
 
@stop @section('footer.append')


 
<script type="text/javascript">

$(function(){
    $('#me8').addClass('active');   

    $('#previous').click(function() {
    	 
		$('#month').val( $('#cmonth').text());
		input = $("<input>").attr("type", "hidden").attr("name", "direction").val("-");
		$('#form1').append($(input));
		$("#form1").submit(); 
     
    
    }); 

    $('#next').click(function() {
   	 	 
		$('#month').val( $('#cmonth').text());
		input = $("<input>").attr("type", "hidden").attr("name", "direction").val("+");
		$('#form1').append($(input));
		 
     
    
    }); 	

    $('#year').change(function() {
    	yr = $('#year').val();
    	$('#month').val( $('#cmonth').text());
		input = $("<input>").attr("type", "hidden").attr("name", "year").val(yr);
	 	$('#form1').append($(input));
	 	this.form.submit();
		//$("#form1").submit();  
    }); 	 
 
});

function changeWorkday(day, workflag) {
	val = $('#id'+day).text();
	
	if(val == "{{Lang::get('mowork.work')}}") {
		$('#id'+day).html("<b>{{Lang::get('mowork.dayoff')}}</b>");
		$('#day'+day).val('0');
	} else {
		$('#id'+day).html("{{Lang::get('mowork.work')}}");
		$('#day'+day).val('1');
	}
}
</script>


@stop