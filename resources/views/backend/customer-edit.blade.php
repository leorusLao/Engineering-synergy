@extends('backend-base') @section('css.append') @stop

@section('content')

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	<script type="text/javascript">
	 window.opener.location.reload();
    </script>
	@endif
     
<div class="text-center"><b>{{Lang::get('mowork.customer')}}: {{$row->company_name}}</b></div>  
<form action="/dashboard/customer/edit/{{$token}}/{{$id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.contact')}}</i>
</div>
<input class="form-control" name="fullname" type="text" value="{{$row->contact_person}}" id="fullname" />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.phone')}}</i>
</div>
<input class="form-control" name="phone" type="text" value="{{$row->phone}}" id="phone" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.email')}}</i>
</div>
<input class="form-control" name="email" type="text" value="{{$row->email}}" id="email" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.wechat')}}</i>
</div>
<input class="form-control" name="wechat" type="text" value="{{$row->wechat}}"  />
</div>
  
<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
</div>

 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
    <div class="text-center" onclick="window.close()"><p class="btn">{{Lang::get('mowork.close')}}</p></div>
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
    		 
    	  fullname = $.trim($('#fullname').val()); 
    	  if(fullname.length < 1) {
    	  	errors += "{{Lang::get('mowork.contact_required')}} \n";	
    		}

    	  phone = $.trim($('#phone').val()); 
    	  if(phone < 1) {
    	     errors += "{{Lang::get('mowork.phone_required')}} \n";	
    	  }

    	  email = $.trim($('#email').val()); 
    	  if(email < 1) {
    	     errors += "{{Lang::get('mowork.email_required')}} \n";	
    	  }
    	  
    	  if(errors.length > 0) {
    		alert(errors);
    		return false;
    	  }
    	  return true;
    	  
    }
    
</script>


@stop