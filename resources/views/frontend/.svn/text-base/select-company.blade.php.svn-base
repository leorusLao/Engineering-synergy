@extends('front-base')

@section('css.append')
 
@stop

@section('main-body')
<div class="container" style="margin-top:30px;">
<div class="row margin-t50 margin-bottom-20">
<div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title text-center">{{Lang::get('mowork.select_company')}}</h3>
</div>
  
<div class="panel-body" id='mainbody'>
 
@if(Session::has('login_failed') )
       <div class="alert alert-danger">
       <ul>
       <li>{{ Session::get('login_failed')  }}</li>
       </ul>
       </div>
 @endif
<form action="/select-company"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

@foreach($rows as $row)
 
<div class="col-xs-12 col-sm-12"><h4>{{$row->company_name}}
<input type="checkbox" name="company[]"  value="{{$row->company_id}}" class="chb" id="company{{$row->company_id}}" /></h4>
</div>

@endforeach
<div class="col-xs-12 col-sm-12"><h4><a href="/dashboard/logout">{{Lang::get('mowork.logout')}}</a></h4></div>

<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info btn-block" value="{{Lang::get('mowork.company_entry')}}">
</div>
<div class="text-center"><a href="/lost-password"><!--{{Lang::get('mowork.lost_password')}} --></a></div>
<input name="_token" type="hidden" value="{{ csrf_token() }}">
 
<input name="client_type" value="1" type="hidden">
<input name="company_id" value="" type="hidden" id="company_id"> 
</fieldset>
</form>
</div>
</div>
</div>
</div>
</div>
@stop

@section('footer.append')

<script type='text/javascript'>
 $(".chb").change(function() {
    $(".chb").prop('checked', false);
    $(this).prop('checked', true);
    company_id = $("input[type='checkbox']:checked").val();
    $('#company_id').val(company_id);
        
 });

  $(function() {
	  
	 
  });

  
  function validateForm(){
    var errors = '';
 
    if ($('.chb:checked').length < 1) {  
        
    	alert("{{Lang::get('mowork.select_company')}}");
    	 
    	return false;
    }
     
     
	return true;
    
  }
 
  
  </script>
@stop
