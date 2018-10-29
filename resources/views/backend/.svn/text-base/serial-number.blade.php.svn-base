@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  
	@if(Session::has('result'))
	<div class="text-center text-danger">{{Session::get('result')}}</div>
	@endif @if(count($rows))
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.prefix')}}</th>
				<th>{{Lang::get('mowork.description')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.cycle')}}</th>
				<th>{{Lang::get('mowork.year_format')}}</th>
				<th>{{Lang::get('mowork.month_format')}}</th>
				<th>{{Lang::get('mowork.day_flag')}}</th>
				<th>{{Lang::get('mowork.serial_length')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif @foreach($rows as $row)
 
			<tr>

				<td>{{ $row->prefix }}</td>
				<td>{{ $row->description }}</td>
				<td>{{ $row->description_en }}</td>
				<td>{{ $row->cycle . '/' . $row->cycle_en }}</td>
                <td>{{ $row->yyyy }}</td>
                <td>{{ $row->mm }}</td>
                <td>@if($row->dd == 0 ) &#10007; @else &#10004; @endif</td>
                <td>{{ $row->serial_length }}</td>
			</tr>

			@endforeach @if(count($rows))

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