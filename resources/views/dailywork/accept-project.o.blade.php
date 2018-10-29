@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12">
      
   <div class="clearfix margin-b20"></div> 
    @if(count($rows) > 0)
   	     
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
	 
	 
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
		    <th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
		    <th nowrap="nowrap">{{Lang::get('mowork.upper_company')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.project_manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.quantity')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.fixture')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.gauge')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.mould')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.start_date')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.end_date')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.select').Lang::get('mowork.calendar')}}</th> 
            <th nowrap="nowrap">{{Lang::get('mowork.associate')}}</th>
            </tr>
			</thead>

			<tbody>
 			 
			@foreach($rows as $row)

				<tr>
				 
				<td>{{ $row->proj_code }}</a></td>
				<td>{{ $row->proj_name }}</td>
				<td>{{$row->upper_company}}</td>
				<td>{{ $row->proj_manager}}</td>
				 
				<td>{{ $row->part_name }}</td>
				<td>{{ $row->quantity }}</td>
			 	<td>{{$row->jig }}</td>
			 	<td>{{$row->gauge }}</td>
			 	<td>{{$row->mold }}</td>
			 	<td>{{$row->start_date }}</td>
			 	<td>{{$row->end_date }}</td> 
			 	<td>
			 	   @if(!$row->supplier_accepted)
			 	   <select name='calendar_id' id='cal{{$row->id}}'>
			 	    <option></option>
			 	    @foreach($calendars  as $cal)
			 	      <option value="{{$cal->cal_id}}">
			 	      {{$cal->cal_name}}</option>
			 	    @endforeach
			 	   </select>
			 	   @endif 
			 	</td> 
			    <td class="text-center"> 
			    @if($row->supplier_accepted)
			      {{Lang::get('mowork.associated')}}
			    @else
	              <span id="jaa{{$row->id}}"></span>
	              <span id="jab{{$row->id}}"> 
	              <a href='#' onclick="acceptProject({{$row->proj_id}}, {{$row->id}},{{$row->upper_company_id}},'{{$row->upper_company}}', '{{ $row->part_name }}','{{$row->start_date }}','{{$row->end_date }}')">
	              <span class="glyphicon glyphicon-ok"></span>
		          </a>
		          </span>
			 	@endif
			 	</td>
 	 		</tr>
              
	 		@endforeach

	 		</tbody>

	 		</table>

	 		<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
    
</div>
@else
 <h5 class="text-danger"><b>{{Lang::get('mowork.no_incoming_project')}}</b></h5>
@endif 
</div>
  
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	$(".check").change(function() {
    	    $(".check").prop('checked', false);
    	    $(this).prop('checked', true);
    	}); 

   	    //above prevent datepicker from fireing 'show.bs.modal';avoid conflict with Datepicker show.bs.modal
        $('#me3').addClass('active');  
         
    });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm() {
        var errors = '';
       
        $cbx_via = $("input:checkbox[name='cbx[]']"); 
        if(! $cbx_via.is(":checked") ){
        	  errors += "{{Lang::get('mowork.supplier_required')}}\n";
        }  

        
        if(errors.length > 0) {
      	alert(errors);
    	return false;
        }
        return true;
        
     }

    function acceptProject(proj_id, project_detail_id, upper_company_id, upper_company_name, part_name, start_date, end_date) {
        calendar_id = $('#cal' + project_detail_id).val();
      
        if(! calendar_id > 0) {
			 alert("{{Lang::get('mowork.cal_required')}}");
		     return;
        }
       
   	    $.ajax({
 	        type:"POST",
 	        url : '{{url("/dashboard/accept-project")}}',
 	        data : { proj_id: proj_id,
 	 	             project_detail_id: project_detail_id,
 	        	     upper_company_id: upper_company_id,
                     upper_company_name: upper_company_name,
                     part_name: part_name,
                     calendar_id: calendar_id,
                     start_date: start_date,
                     end_date: end_date,
 	                 _token: "{{csrf_token()}}", 
   	                 submit: "submit" },
 	        dataType: 'json',
 	        success : function(result) {
 	 	        
 	         	for(var ii in result){
     	         	 res = result[ii];
     	             $('#jaa'+res).text("{{Lang::get('mowork.associated')}}");
                     $('#jab'+res).css("display","none");
                     $('#cal'+res).css("display","none");
         	         
 	        	}
 	        },
 	        error: function() {
 	            //do logic for error
 	        }
 	    }) 
  
    }
 
</script>
 
@stop