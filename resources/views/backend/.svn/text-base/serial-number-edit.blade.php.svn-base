@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/other-setup/serial-number/edit/{{$token}}/{{$row->id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.prefix')}}</i>
</div>
<input class="form-control" name="prefix" type="text" value="{{$row->prefix}}" id="prefix" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.description')}}</i>
</div>
<input class="form-control" name="description" type="text" value="{{$row->description}}" id="description" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}}</i>
</div>
<input class="form-control" name="description_en" type="text" value="{{$row->description_en}}"  />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.year_format')}}</i>
</div>
	<select name="yyyy" id="yyyy" class="form-control">
	<option value="YYYY" @if($row->yyyy == 'YYYY') selected @endif>YYYY</option>
	<option value="YY" @if($row->yyyy == 'YY') selected @endif>YY</option>
	</select>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.month_format')}}</i>
</div>
	<select name="mm" id="mm" class="form-control">
		<option value="MM" @if($row->mm == 'MM') selected @endif>MM(01-12)</option>
		<option value="M" @if($row->mm == 'M') selected @endif>M(1-12)</option>
	</select>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.day_flag')}}</i>
</div>&nbsp;&nbsp;
<input type="checkbox" class="form-contrl" name="dayflag" value="{{$row->dd}}" @if($row->dd == 1) checked @endif>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.serial_length')}}</i>
</div>
	<select name="serial_length" id="serial_length" class="form-control">
		<option value="2" @if($row->serial_length == '2') selected @endif>2</option>
		<option value="3" @if($row->serial_length == '3') selected @endif>3</option>
		<option value="4" @if($row->serial_length == '4') selected @endif>4</option>
		<option value="5" @if($row->serial_length == '5') selected @endif>5</option>
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
    		 
    	  prefix = $.trim($('#prefix').val()); 
    	  if(prefix.length < 1) {
    	  	 errors += "{{Lang::get('mowork.prefix_required')}} \n";	
    	  } else if (prefix.length > 2) {
		     errors += "{{Lang::get('mowork.prefix_max')}} \n";	
          }

    	  description = $.trim($('#description').val()); 
    	  if(description < 1) {
    	     errors += "{{Lang::get('mowork.description_required')}} \n";	
    	  }
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }
    
</script>


@stop