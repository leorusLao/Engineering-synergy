@extends('pfadmin-base')
 
@section('css.append')
<link href="/asset/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
@stop 

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
 <div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.node_type')}}"><span
		class="glyphicon glyphicon-plus">{{Lang::get('mowork.add').Lang::get('mowork.node_type')}}</span></a>
 </div>

	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))

    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.node').Lang::get('mowork.type_name')}}</th>
		       	<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.ctrl_by_dep')}}</th>
				<th>{{Lang::get('mowork.fore_color')}}</th>
  				<th>{{Lang::get('mowork.back_color')}}</th>
  				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif
			
			@foreach($rows as $row)
 
			<tr>
				<td>{{ $row->type_name }}</td>
				<td>{{ $row->type_name_en }}</td>
				<td><?php $deps = explode(',', $row->ctrl_by_dep);
				     $ds = '';
				     foreach ($deps as $dep) {
				     	foreach ($departments as $department) {
				     		if($dep == $department->dep_id) {
				     			$ds .= $department->name.',';
				     		}
				     	}
				     }
				     $ds = rtrim($ds,',');
				     echo $ds;
				?>
				</td>
			 
 
				<td><div style="margin-lef:5px;background-color:{{$row->fore_color}} ;"> &nbsp;</div></td>
				<td><div style="margin-lef:5px;background-color:{{$row->back_color}} ;"> &nbsp;</div></td>
			  	<td>
				@if($row->company_id > 0)
				 <a href="/dashboard/project-config/node-type/edit/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			     <a href="/dashboard/project-config/node-type/delete/{{hash('sha256',$salt.$row->type_id.$row->company_id)}}/{{$row->type_id}}" onclick="return confirm('{{Lang::get('mowork.want_delete')}}')"><span class="glyphicon glyphicon-trash"></span></a>
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

<div class="modal fade" id="formholder">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add')}}{{Lang::get('mowork.node_type')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/project-config/node-type' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
					<div class="col-sm-10">	
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.node').Lang::get('mowork.type_name')}} *
						</div>
						 
						<input type="text" class="form-control" name="type_name"
							 
							title="{{Lang::get('mowork.node_type')}}" id='type_name'>
					</div>
					</div>
					
					<div class="col-sm-10">	
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.node').Lang::get('mowork.in_english')}}
						</div>
						 
						<input type="text" class="form-control" name="type_name_en"
							 
							title="{{Lang::get('mowork.node_type')}}" id='type_name_en'>
					</div>
					</div>
 				 	
					<div class="col-sm-10">	
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.fore_color')}}
						</div>
						 
					    	
					   <div id="cp1" class="input-group colorpicker-component">
                          <input type="text"  name='forecolor' value="#000000" class="form-control" />
                             <span class="input-group-addon"><i></i></span>
                       </div>
 					</div>	
				    </div>
					
					<div class="col-sm-10">	
					<div class="form-group input-group">
					    <div class="input-group-addon">
						{{Lang::get('mowork.back_color')}}
						</div>
					 
					    <div id="cp2" class="input-group colorpicker-component">
                          <input type="text" name='backcolor' value="#00AABB" class="form-control" />
                             <span class="input-group-addon"><i></i></span>
                       </div>
 					</div>	
					</div>
					<div class="clearfix"></div>
					
					<div class="form-group margin-l20">
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
 
<script src="/asset/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	$('#cp1').colorpicker();  
    	$('#cp2').colorpicker();
    	   
        $('#me8').addClass('active');   
        /*
         offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2
	      
          $('html,body').animate({
        	   scrollTop: offset > 0 ? offset:1000
          }, 200);
       */
             

     });


    function validateForm(){
      var errors = '';
    	 
      var type_name = $.trim($('#type_name').val()); 
      if(type_name.length < 1) {
      	errors += "{{Lang::get('mowork.typename_rquired')}} \n";	
      }
       
      if(errors.length > 0) {
    	alert(errors);
    	return false;
      }
      return true;
      
    }
</script>


@stop