@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/department/edit/{{$token}}/{{$row->dep_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.department_code')}}</i>
</div>
<input class="form-control" name="dep_code" type="text" value="{{$row->dep_code}}" id="dep_code" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.department_name')}}</i>
</div>
<input class="form-control" name="dep_name" type="text" value="{{$row->name}}" id="dep_name" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.upper_department')}}</i>
</div>
 
{{Form::select('upper',$departmentList, $row->upper_id?$row->upper_id:0,array('class' => 'form-control', 'id' =>  'upper' ))}}
 
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.manager')}}</i>
</div>
<select class="form-control" name="manager">
 <option></option>
 @foreach($employees as $man)
   <option value="{{$man->uid}}" @if($man->uid == $row->manager) selected @endif>{{$man->fullname}}</option>
 @endforeach
</select>
</div>
 
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
</div>

 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
    
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