@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
    
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
    @if(count($rows))
	<div class="table-responsive table-scrollable">
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.currency_code')}}</th>
				<th>{{Lang::get('mowork.currency_name')}}</th>
				<th>{{Lang::get('mowork.english_name')}}</th>
				<th>{{Lang::get('mowork.symbol')}}</th>
				<th>{{Lang::get('mowork.exchange_rate')}}</th>
				<th>{{Lang::get('mowork.local_currency')}}</th>
			 
			</tr>
		</thead>

		<tbody>

		 
	    @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->code }}</td>
				<td>{{ $row->name }}</td>
				<td>{{ $row->name_en }}</td>
				<td>{{ $row->symbol }}</td>
				<td>{{ $row->rate }}</td>
				<td>{{ $row->local_currency_flag? Lang::get('mowork.yes') : Lang::get('mowork.no')}}</td>
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

    function validateForm(){
    	  var errors = '';
    		 
    	  dep_code = $.trim($('#dep_code').val()); 
    	  if(dep_code.length < 1) {
    	  	errors += "{{Lang::get('mowork.depcode_required')}} \n";	
    		}

    	  dep_name = $.trim($('#dep_name').val()); 
    	  if(dep_name < 1) {
    	     errors += "{{Lang::get('mowork.depname_required')}} \n";	
    		}
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }
    
</script>


@stop