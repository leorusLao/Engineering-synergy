@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
   	 
	<div class="text-center text-danger marging-b20" id="result"></div>
	 
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
    @if(count($rows))
	<div class="table-responsive table-scrollable">
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.department_code')}}</th>
				<th>{{Lang::get('mowork.department_name')}}</th>
				<th>{{Lang::get('mowork.calendar')}}</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

		 
	    @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->dep_code }}</td>
				<td>{{ $row->name }}</td>
			 	<td>
			 	 <select name="cal{{$row->dep_id}}" id="cal{{$row->dep_id}}" disabled  onchange="setDepartmentCal({{$row->dep_id}})">
			 	 <option value="0"></option>
			 	 @foreach($cals as $cal)
                    <option value="{{$cal->cal_id}}"  @if ($row->cal_id == $cal->cal_id) selected @endif >
                      {{$cal->cal_name}}
                    </option>
                 @endforeach
                 </select>
                </td>
		 		<td><a href="#" onclick="enableSelect({{$row->dep_id}})"><span id="edit{{$row->dep_id}}">{{Lang::get('mowork.edit')}}</span></a>
				</td>			 
			</tr>

		@endforeach

		</tbody>

	</table>

	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>

 </div>


@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function enableSelect(dep_id) {
    	$('#cal' + dep_id).prop('disabled', false);
    } 

    function setDepartmentCal(dep_id) {
         
          
    	 calendar_id = $('#cal' + dep_id).val();
     	 
         if( calendar_id == 0) {
           return;
         }
         //alert('cal=='+calendar_id+';dep==='+dep_id);
          $.ajax({
   	        type:"POST",
   	        url : '{{url("/dashboard/project-config/department-calendar")}}',
   	        data : {
   	   	           dep_id: dep_id, 
                   cal_id: calendar_id,
	   	            _token: "{{csrf_token()}}",
	   	           submit: "submit"
                   
   	   	   	   	   },
   	        dataType: "json",
   	        success : function(result) {
   	   	       
   	   	       var txt = '';
   	   	       for(var ii in result){
  	        		txt = txt + result[ii];
  	        	}
   	            $('#result').text(txt); 
   	           
   	        },
   	       error: function(xhr, status, error) {
   	    	  alert(error);
             },
   	    });
  
    }
   
</script>


@stop
