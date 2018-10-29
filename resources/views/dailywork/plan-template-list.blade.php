@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-8 col-sm-offset-2">
 
 <div class="margin-b20">
	<a href='#formholder'  rel="tooltip"
		data-placement="right" data-toggle="modal" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.plan_template')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add').Lang::get('mowork.plan_template')}}</span></a>
 </div>

	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))

    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.template_code')}}</th>
				<th>{{Lang::get('mowork.template_name')}}</th>
				<th>{{Lang::get('mowork.template_type')}}</th>
				<th>{{Lang::get('mowork.type_name')}}</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
  				<th>{{Lang::get('mowork.template_maker')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif
			
			@foreach($rows as $row)
 
			<tr>
				<td>{{ $row->template_code }}</td>
				<td>{{ $row->template_name }}</td>
				<td>{{ $row->template_type == 2? Lang::get('mowork.node_template'):Lang::get('mowork.plan_template')}}</td>
				<td>{{ $row->template_type == 2? $row->node_type_name: $row->plan_type_name }}</td>
			  	<td>
				@if($row->company_id > 0)
				 <a href="/dashboard/template/edit/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;&nbsp;
			     <a href="/dashboard/template/delete/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')"><span class="glyphicon glyphicon-trash"></span></a>
			    @endif
			    </td>
			    <td class="text-center">
			    @if($row->company_id > 0)
			     <a href="/dashboard/template/make/{{hash('sha256',$salt.$row->id)}}/{{$row->id}}"><span class="glyphicon glyphicon-pencil"></span></a>
			    @endif
			    </td>
			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>
    </div>
    
	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
	@endif

</div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.plan_template')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/template-list' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_code')}}</i>
						</div>
						<input type="text" class="form-control" name="template_code"
							 
							title="{{Lang::get('mowork.template_code')}}" id='template_code'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_name')}}</i>
						</div>
						<input type="text" class="form-control" name="template_name"
					 		title="{{Lang::get('mowork.template_name')}}" id='template_name'>
					</div>
					
					<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_type')}}</i>
						</div>
						  <div class="text-left"> &nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" name="template_type" value="1" checked  id="type1">{{Lang::get('mowork.plan_template')}}
						  &nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" name="template_type" value="2" id="type2">{{Lang::get('mowork.node_template')}}  
						  </div>
					</div>
				 	
				 	<div class="form-group input-group" id="plan_type">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.please_select')}}</i>
						</div>
				 		<select name="plan_type_id" class="form-control">
						@foreach($planTypeList as $res)
						  <option value="{{$res->type_id}}">{{$res->type_name}}</option>
						@endforeach
						</select>
					</div>
					
					<div class="form-group input-group" id="node_type">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.please_select')}}</i>
						</div>
					 	<select name="node_type_id" class="form-control">
						@foreach($nodeTypeList as $res)
						  <option value="{{$res->type_id}}">{{$res->type_name}}</option>
						@endforeach
						</select>
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
    $("[rel=tooltip]").tooltip({animation:false}); 
    $(function(){
        $('#me4').addClass('active');   
            $("#node_type").hide();   
            $("#type1").click(function(){
            	$("#plan_type").show();
            	$("#node_type").hide();
            });
            $("#type2").click(function(){
            	$("#plan_type").hide();
            	$("#node_type").show();
            });
         
     });
 
    function validateForm(){
      var errors = '';
    	 
      var template_code = $.trim($('#template_code').val()); 
      if(template_code.length < 1) {
      	errors += "{{Lang::get('mowork.tmplcode_required')}} \n";	
    	}

      var template_name = $.trim($('#template_name').val());
      if(template_name < 1) {
         errors += "{{Lang::get('mowork.tmplname_required')}} \n";	
      }

      template_type = $('input[name=template_type]:checked').val();   
     
     
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
      return true;
      
    }
</script>


@stop