@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12">
  
    
<div class="margin-b20">
	<a href="/dashboard/customer/select"  rel="tooltip"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.customer')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add').Lang::get('mowork.customer')}}</span></a>
</div>
    
	@if(Session::has('result'))
	<h4 class="text-danger text-center">{{Session::get('result')}}</h4>
	@endif 
	
	@if(count($rows))

	<div class="table-responsive table-scrollable"> 
    <table class="table dataTable table-striped display table-condensed">

		<thead>
			<tr>
				<th nowrap='nowrap'>{{Lang::get('mowork.customer_name')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.industry')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.type')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.biz_number')}}</th>
			 	<th nowrap='nowrap'>{{Lang::get('mowork.contact')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.phone')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.email')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.country')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.province')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.city')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.address')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.postcode')}}</th>
				<th>{{Lang::get('mowork.action')}}</th>
			</tr>
		</thead>

		<tbody>
    @else
      <div class="text-center text-danger">{{Lang::get('mowork.without_customer')}}</div>
	@endif 
			
			@foreach($rows as $row)
 
			<tr>

				<td>{{ $row->company_name }}</td>
				<td>{{ $row->industry_name }}</td>
				<td>{{ $row->cust_type }}</td>
				<td>{{ $row->reg_no}}</td>
				<td>{{ $row->contact_person}}</td>
				<td>{{ $row->phone}}</td>
				<td>{{ $row->email}}</td>
				<td>{{ $row->country_name}}</td>
				<td>{{ $row->province_name}}</td>
				<td>{{ $row->city_name}}</td>
				<td>{{ $row->address}}</td>
				<td>{{ $row->postcode}}</td>
                <td><a rel="tooltip" data-placement="right" data-original-title="Edit" href="/dashboard/customer/edit/{{hash('sha256',$salt.$row->cust_company_id)}}/{{$row->cust_company_id}}" target="_blank"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					<a rel="tooltip" data-placement="right" data-original-title="Delete" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')" href="/dashboard/customer/delete/{{hash('sha256',$salt.$row->cust_company_id)}}/{{$row->cust_company_id}}"><span class="glyphicon glyphicon-trash"></span></a>
				</td>	
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

</script>


@stop