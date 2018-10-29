@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/other-setup/tool-file/edit/{{$token}}/{{$row->id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.folder_type')}}</i>
</div>
<input class="form-control" name="folder_code" type="text" value="{{$row->folder_code}}" id="folder_code" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.file_type')}}</i>
</div>
<input class="form-control" name="filetype" type="text" value="{{$row->filetype}}" id="filetype" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}}</i>
</div>
<input class="form-control" name="filetype_en" type="text" value="{{$row->filetype_en}}"  />
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
    		 
    		 
      	  var folder_code = $.trim($('#folder_code').val()); 
      	  if(folder_code.length < 1) {
      	  	errors += "{{Lang::get('mowork.foldertype_required')}} \n";	
      		}

      	  var filetype = $.trim($('#filetype').val()); 
      	  if(filetype < 1) {
      	     errors += "{{Lang::get('mowork.filetype_required')}} \n";	
      		}
      	  
      	  if(errors.length > 0) {
      		alert(errors);
      		return false;
      	  }
      	  return true;
    	  
    }
    
</script>


@stop