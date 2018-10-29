@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/issue-config/issue-source/edit/{{$token}}/{{$row->id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.source_code')}}</i>
</div>
<input type="text" class="form-control" name="code"
							value="{{$row->code}}"
							title="{{Lang::get('mowork.source_code')}}" id='code'>
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.source_name')}}</i>
</div>
<input type="text" class="form-control" name="name"
							value="{{$row->name}}"
							title="{{Lang::get('mowork.source_name')}}" id='name'>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.description')}}</i>
</div>
<input type="text" class="form-control" name="description"
							value="{{$row->description}}"
							title="{{Lang::get('mowork.description')}}" id='description'>
</div>
  
<input name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
</div>
 
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