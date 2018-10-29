@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
 <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.position')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.position')}}</span></a>
 </div>

	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))
     @if(isset($errors))
            	<h4>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h4>
    @endif
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.position')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif 
			@foreach($rows as $row)
 
			<tr>
				<td>{{ $row->position_title }}</td>
				<td>{{ $row->position_title_en }}</td>
			 	<td>
				@if($row->company_id > 0)
				 <a href="/dashboard/employee/position/edit/{{hash('sha256',$salt.$row->position_id)}}/{{$row->position_id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			     <a href="/dashboard/employee/position/delete/{{hash('sha256',$salt.$row->position_id)}}/{{$row->position_id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')"><span class="glyphicon glyphicon-trash"></span></a>
			    @endif
			    </td>
			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>
    </div>
   
	<div class="clearfix"></div>
	@endif

</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.position')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/employee/position' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.position')}}</i>
						</div>
						<input type="text" class="form-control" name="position"
							 
							title="{{Lang::get('mowork.position')}}" id='position'>
					</div>
					
					<div class="form-group input-group">
						<div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}}</i>
						</div>
						<input type="text" class="form-control" name="position_en"
							placeholder="{{Lang::get('mowork.english_name')}}"
							title="{{Lang::get('mowork.in_english')}}" id='position_en'>
					</div>
			        <div class="text-center text-danger margin-b20" id="dup"></div>
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

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});
 
    $(function(){
    	$('#me8').addClass('active');   
    });
    
    function validateForm(){
      var errors = '';
    	 
      var position = $.trim($('#position').val()); 
       
      if(position.length < 1) {
      	errors += "{{Lang::get('mowork.position_required')}} \n";	
      }
      position_en = $.trim($('#position_en').val()); 

      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
    
      $('#dup').text(''); 
      existed = false;
      
      $.ajax({
	        type:"GET",
	        url : '/dashboard/employee/check-existed-position',
	        data : { position: position, position_en: position_en, position_id:0 },
	        async: false,
	        dataType: 'json',
	        success : function(result) {
	            alert('success');
	        	for(var ii in result){
	        		if(result[0] == 'existed'){
			        	  error = "{{Lang::get('mowork.position_existed')}}";
	  			          $('#dup').text(error);
	  			          existed = true;  
	  			          break;
			        } 
	        	}
	        },
	        error: function() {
	            //do logic for error
	        	 alert('failed');
	        	 return false; 
	        }
	    });
        
        if(existed) return false;
        
        return true;
  }
</script>


@stop

