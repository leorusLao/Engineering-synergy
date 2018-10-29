@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">

    @if(Session::has('result'))
    <div class="text-danger text-center">{{Session::get('result')}}</div>
    @elseif (isset($restult))
    <div class="text-danger text-center">{{$result}}</div>
    @endif
     
       
    <div class="col-sm-6">
    <form action="/dashboard/project-config/department-calendar" method="post" id="form1" name='form1'>

        <select id='department' name='department' class="form-control" style="width:160px; float:left; margin-right:10px;">
         @foreach ($departments as $key => $value)
           <option value="{{$value['dep_id']}}" @if($value['dep_id'] == $dep_id) selected @endif>
           {{$value['name']}}
           </option>
         @endforeach
        </select>

        <select id='year' name='year' class="form-control" style="width:80px; float:left; margin-right:10px;">
         @foreach ($yearList as $op => $val)
           <option value="{{$val}}" @if($val == $selectedY) selected @endif>
           {{$val}}
           </option>
         @endforeach
        </select>   
     
        <button class="btn" id="previous" title="{{Lang::get('mowork.previous_month')}}">
        <span class="glyphicon glyphicon-chevron-left"></span></button>
        <button class="btn"  id="next" title="{{Lang::get('mowork.next_month')}}">
        <span class="glyphicon glyphicon-chevron-right"></span></button>
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <input name="month" id="month" type="hidden">
         
    </form> 
     
    </div>
    <div class="col-sm-6">
    <div class="text-warning">1. {{Lang::get('mowork.day_warn_dep1')}}</div>
    <div class="text-warning">2. {{Lang::get('mowork.day_warn2')}}</div>
    </div>
    <div class="clearfix"></div>
    
    <div><h3 class="text-center marging-b20"><span id='cyear'>{{$rows[0]->cal_year}}</span><span>.</span><span id='cmonth'>{{$rows[0]->cal_month}}</span></h3></div>
     
    {{ Form::open(array('url' => '/dashboard/project-config/department-calendar', 'method' => 'post', 'class' => '', 'onsubmit' => 'return validateCheckbox()')) }}
     
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

    
    @for($kk = 0; $kk <  $spacesFirstLine; $kk++)
    <td class="">&nbsp;</td>
    @endfor
    
    <?php $dd = 0;?>
    @foreach($rows as $row)
          
        @if($spaces % 7 == 0)   
            <tr style="height: 70px">
        @endif   
                      
           <td style="cursor:pointer; @if($row->dow == 1 || $row->dow == 7) background:#ccc; @endif;" onclick="changeWorkday({{$row->cal_day}}, {{$row->is_workday}})">{{$row->cal_day}}<br>
              <!-- if has customized cal, use it first -->
            @if($realSchedule)
                 @if($realSchedule[$dd] == 1)
                 <span id="id{{$row->cal_day}}" >{{Lang::get('mowork.work')}}</span>
                 <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$realSchedule[$dd]}}">
                 @else
                 <span id="id{{$row->cal_day}}" ><b style='color: #0000F8' >{{Lang::get('mowork.dayoff')}}</b></span>
                 <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="{{$realSchedule[$dd]}}">
                 @endif
              @else
              <!--  use default calendar -->
                 @if($row->is_workday)
                 <span id="id{{$row->cal_day}}" >{{Lang::get('mowork.work')}}</span>
                 <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="1">
                 @else
                 <span id="id{{$row->cal_day}}" ><b style='color: #0000F8' >{{Lang::get('mowork.dayoff')}}</b></span>
                 <input type="hidden" name="day{{$row->cal_day}}" id="day{{$row->cal_day}}" value="0">
                 @endif
              @endif
           </td>
           <?php $spaces++; ?>
                   
        @if($spaces % 7 == 0)    
            </tr>
        @endif
        <?php $dd++; ?>    
    @endforeach 
        </tbody>
    </table>
    <input type="hidden" name="year" value="{{$selectedY}}">
    <input type="hidden" name="month" value="{{$selectedM}}">
    <input type="hidden" name="department" value="{{$dep_id}}">
    <div>
    @if(!empty($dep_id))<input class="btn btn-info" type="submit" name="submit" value="{{Lang::get('mowork.save')}}">@endif
    </div>
    {{Form::close()}}
     
   </div>
  
   <div class="clearfix"></div>
     
 
</div>
 
@stop @section('footer.append')


 
<script type="text/javascript">

$(function(){
    $('#previous').click(function() {         
        $('#month').val( $('#cmonth').text());
        input = $("<input>").attr("type", "hidden").attr("name", "direction").val("-");
        $('#form1').append($(input));       
    }); 

    $('#next').click(function() {         
        $('#month').val( $('#cmonth').text());
        input = $("<input>").attr("type", "hidden").attr("name", "direction").val("+");
        $('#form1').append($(input));
    });     

    $('#year').change(function() {
        yr = $('#year').val();
        department = $('#department').val();
        $('#month').val( $('#cmonth').text());
        input = $("<input>").attr("type", "hidden").attr("name", "year").val(yr);
        input_dep = $("<input>").attr("type","hidden").attr("name","department").val(department);
        $('#form1').append($(input));
        $('#form1').append($(input_dep));
        this.form.submit();  
    });      

    $('#department').change(function() {
        yr = $('#year').val();
        department = $('#department').val();
        $('#month').val( $('#cmonth').text());
        input = $("<input>").attr("type", "hidden").attr("name", "year").val(yr);
        input_dep = $("<input>").attr("type","hidden").attr("name","department").val(department);
        $('#form1').append($(input));
        $('#form1').append($(input_dep));
        this.form.submit();  
    });      
 
});

function changeWorkday(day, workflag) {
    val = $('#id'+day).text();
    
    if(val == "{{Lang::get('mowork.work')}}") {
        $('#id'+day).html("<b style='color: #0000F8' >{{Lang::get('mowork.dayoff')}}</b>");
        $('#day'+day).val('0');
    } else {
        $('#id'+day).html("{{Lang::get('mowork.work')}}");
        $('#day'+day).val('1');
    }
}
</script>


@stop