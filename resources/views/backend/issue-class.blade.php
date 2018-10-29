@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-8 col-sm-offset-2">

	<!-- <ul class="nav nav-justified margin-b30">
		<li><a href="/dashboard/issue-config/">{{Lang::get('mowork.issue_source')}}</a></li>
		<li><a href="/dashboard/issue-config/issue-class">{{Lang::get('mowork.issue_class')}}</a></li>
 	</ul> -->

<div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title="{{Lang::get('mowork.add')}}{{Lang::get('mowork.issue_class')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add')}}{{Lang::get('mowork.issue_class')}}</span></a>
</div>
    
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif

 @if(count($rows))
	<div class="table-responsive table-scrollable">
      <table class="table dataTable table-striped display table-bordered table-condensed">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.category_code')}}</th>
				<th>{{Lang::get('mowork.category_name')}}</th>
				<th>{{Lang::get('mowork.description')}}</th>
				<th>{{Lang::get('mowork.action')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif @foreach($rows as $row)
 
			<tr>
				<td>{{ $row->code }}</td>
                <td>{{ $row->name }}</td>
				<td>{{ $row->description }}</td>
				<td><a href="/dashboard/issue-config/issue-class/edit/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					<a href="/dashboard/issue-config/issue-class/delete/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-trash"></span></a>
				</td>			 
				
			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>

	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
  @endif

</div>
</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.issue_class')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/issue-config/issue-class' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group">
						<input type="text" class="form-control" name="code"
							placeholder="{{Lang::get('mowork.category_code')}}"
							title="{{Lang::get('mowork.category_code')}}" id='code'>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="name"
							placeholder="{{Lang::get('mowork.category_name')}}"
							title="{{Lang::get('mowork.category_name')}}" id='name'>
					</div>
	 	 			<div class="form-group">
						<input type="text" class="form-control" name="description"
							placeholder="{{Lang::get('mowork.description')}}"
							title="{{Lang::get('mowork.description')}}" id='description'>
					</div>
					<div class="form-group">
						<input type="submit" class="form-control btn-info" name="submit"
							value="{{Lang::get('mowork.add')}}">
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
				</form>
			</div>
			<div class="modal-footer"></div>
			<div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div>
		</div>
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
    		 
    	  var  code = $.trim($('#code').val()); 
    	  if(code.length < 1) {
    	  	errors += "{{Lang::get('mowork.sourcecode_required')}} \n";	
    		}

    	  var name = $.trim($('#name').val()); 
    	  if(name < 1) {
    	     errors += "{{Lang::get('mowork.sourcename_required')}} \n";	
    		}
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }
    
</script>


@stop