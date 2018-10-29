@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">

	<ul class="nav nav-justified margin-b30">
		<li class='active'><a href="/dashboard/admin-region">{{Lang::get('mowork.country')}}</a></li>
		<li><a href="/dashboard/admin-region/province">{{Lang::get('mowork.province')}}{{Lang::get('mowork.state')}}</a></li>
		<li><a href="/dashboard/admin-region/city">{{Lang::get('mowork.city')}}</a></li>

	</ul>

	@if(Session::has('result'))
	<div class="alert alert-danger">{{Session::get('result')}}</div>
	@endif @if(count($rows))

	<table class="table data-table display sort table-responsive">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.country_name')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.iso_code')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif @foreach($rows as $row)
 
			<tr>

				<td>{{{ $row->name }}}</td>

				<td>{{{ $row->name_en }}}</td>

				<td>{{{ $row->iso_code2 }}}</td>

			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>

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
