@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
 
    <div class="table-responsive table-scrollable">
	  
	<table class="table data-table table-bordered" id="tb1">
 
		<tbody>
		    <tr>
				<td>{{ Lang::get('mowork.project_number') }}:{{$row->proj_code}}</td>
				<td>{{ Lang::get('mowork.project_name') }}:{{$row->proj_name}}</td> 
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.customer_number') }}:{{$row->customer_id}}</td>
				<td>{{ Lang::get('mowork.customer_name') }}:{{$row->customer_name}}</td> 
		 	</tr>
           <tr>
				<td>{{ Lang::get('mowork.project_manager') }}:{{$row->proj_manager}}</td>
				<td>{{ Lang::get('mowork.member') }}:{{$row->name_list}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.calendar') }}:{{$row->cal_name}}</td>
				<td>{{ Lang::get('mowork.process_trail') }}:{{$row->process_trail}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.mold_sample') }}:{{$row->mold_sample}}</td>
				<td>{{ Lang::get('mowork.trail_production') }}:{{$row->prouction_trail}}</td> 
		 	</tr>
		 	<tr>
				<td colspan=2>{{ Lang::get('mowork.batch_production') }}:{{$row->batch_trail}}</td> 
		 	</tr>
		 	<tr>
				<td colspan=2>{{ Lang::get('mowork.description') }}:{{$row->description}}</td> 
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.approval_person') }}:{{$row->approval_person}}</td> 
				<td>{{ Lang::get('mowork.approval_date') }}:{{$row->approval_date}}</td> 
		 	</tr> 
		</tbody>

	</table>
	 <div>{{Lang::get('mowork.part_information')}}</div>
	 
    <table class="table data-table table-bordered" id="tb2">
            <thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.part_number')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.part_name')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.part_type')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.quantity')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.comment')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.fixture')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.gauge')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.mould')}}</th>
			<th nowrap="nowrap">{{Lang::get('mowork.process_tech')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.resource')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_material')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.material_size')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.shrink')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.surface_process')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_size')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_weight')}}</th>
			</tr>
			</thead>
            <tbody>
            @foreach($parts as $r )
            <tr>
			<td>{{$r->part_code}}</td>
			<td>{{$r->part_name}}</td>
			<td>{{$r->part_type}}</td>
			<td>{{$r->quantity}}</td>
			<td>{{$r->note}}</td>
			<td>{{$r->jig}}</td>
			<td>{{$r->gauge}}</td>
			<td>{{$r->mold}}</td>
			<td>{{$r->processing}}</td>
            <td>{{$r->part_from}}</td>
            <td>{{$r->matertial}}</td>
            <td>{{$r->mat_size}}</td>
            <td>{{$r->shrink}}</td>
            <td>{{$r->surface}}</td>
            <td>{{$r->part_size}}</td>
            <td>{{$r->weight}}</td>
     		</tr>
            @endforeach
            </tbody>
    </table>
</div>
<div class="margin-t20 text-center" onclick="window.close()" style="cursor: pointer">{{Lang::get('mowork.close')}}</div>
</div>
@stop 
  
@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	 
        $('#me4').addClass('active');   
        /*
         offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2
	      
          $('html,body').animate({
        	   scrollTop: offset > 0 ? offset:1000
          }, 200);
       */
             

     });
   
</script>


@stop