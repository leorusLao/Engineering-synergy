@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	<script type="text/javascript">
	 window.opener.location.reload();
    </script>
	@endif
    
<form action="/dashboard/project-config/node/edit/{{$token}}/{{$row->node_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.node_code')}}</i>
</div>
<input class="form-control" name="node_code" type="text" value="{{$row->node_no}}" id="node_code" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.node_type')}}</i>
</div>
<select class="form-control" name="type_id" id="type_id">
   @foreach($nodetypes as $key => $val)
     <option value="{{$key}}" @if($key == $row->type_id) selected @endif>{{$val}}</option>
   @endforeach
</select>
</div>
  
<div class="form-group input-group">
	<div class="input-group-addon">
	<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.node').Lang::get('mowork.name')}}</i>
	</div>
	<input type="text" class="form-control" name="node_name" value="{{$row->name}}"
							 id='node_name'>
</div>
					
<div class="form-group input-group">
	<div class="input-group-addon">
	<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.node').Lang::get('mowork.in_english')}}</i>
	</div>
	<input type="text" class="form-control" name="node_en" value="{{$row->name_en}}"
							 id='node_en'>
</div>  
  
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
</div>

 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
<div class="margin-t20 text-center" onclick="window.close()" style="cursor: pointer">{{Lang::get('mowork.close')}}</div>    
</div>
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 
        $('#me8').addClass('active');   
 
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm(){
    	errors = '';

        node_code = $.trim($('#node_code').val());	 
        type_id = $.trim($('#type_id').val()); 
        node_name = $.trim($('#node_name').val());

        if(node_code.length < 1) {
          errors += "{{Lang::get('mowork.nodecode_required')}} \n";	
        }
        
        if(type_id.length < 1) {
        	errors += "{{Lang::get('mowork.typename_required')}} \n";	
        }

        if(node_name.length < 1) {
            errors += "{{Lang::get('mowork.nodename_required')}} \n";	
        }
         
        if(errors.length > 0) {
      	alert(errors);
      	return false;
        }
        return true;
    	  
    }
    
</script>


@stop