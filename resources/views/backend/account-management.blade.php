@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
    
	@if(Session::has('result'))
	<h4 class="text-danger text-center">{{Session::get('result')}}</h4>
	@endif 
	
	@if(count($rows))

	<div class="table-responsive table-scrollable"> 
    <table class="table dataTable table-striped display table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.role_code')}}</th>
				<th>{{Lang::get('mowork.role_name')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.role_description')}}</th>
				<th>{{Lang::get('mowork.employee_code')}}</th>
			 	<th>{{Lang::get('mowork.bearer')}}</th>
			 	<th>{{Lang::get('mowork.position')}}</th>
		 	    <th>{{Lang::get('mowork.assign_role')}}</th>
			</tr>
		</thead>

		<tbody>
    @else
      <div class="text-center text-danger">{{Lang::get('mowork.without_supplier')}}</div>
	@endif 
			
			@foreach($rows as $row)
 
			<tr>

				<td>{{ $row->role_code }}</td>
				<td>{{ $row->role_name }}</td>
				<td>{{ $row->english }}</td>
				<td>{{ $row->role_description }}</td>
				<td>{{ $row->emp_code }}</td>
				<td>{{ $row->fullname}}</td>
				<td>{{ $row->position_title }}</td>
		        <td class="text-center"> <a href="#" onclick="popwin('{{hash('sha256',$salt.$row->id)}}/{{$row->id}}')"><span class="glyphicon glyphicon-edit"></span></a></td>
			</tr>

			@endforeach 
	@if(count($rows))

		</tbody>

	</table>
    </div>
    
	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
	@endif

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

    function popwin(str)
    {    
    	   var left = (screen.width/2)-(440/2);
           var top = (screen.height/2)-(500/2);
           
    	   window.open("/dashboard/account-management/role-assignment/" + str, 'win'+str, 'height=500,width=440,top='+top+', left='+left);
  	 
    } 

</script>

@stop