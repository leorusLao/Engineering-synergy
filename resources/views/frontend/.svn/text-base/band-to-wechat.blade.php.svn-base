@extends('front-base')

@section('css.append')
 
@stop

@section('main-body')
<div class="container" style="margin-top:30px;">
<div class="row margin-t50 margin-bottom-20">
<div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title text-center">{{Lang::get('mowork.not_band')}}</h3>
</div>
  
<div class="panel-body" id='mainbody'>
 
@if(Session::has('login_failed') )
       <div class="alert alert-danger">
       <ul>
       <li>{{ Session::get('login_failed')  }}</li>
       </ul>
       </div>
 @endif
<form action="/select-company"  method='post' autocomplete='off' role='form'> 
<fieldset>
 
<div class="form-group input-group center-block text-center">
<div class="btn btn-info btn-md text-center" onclick="wechatAuthAndBand()">{{Lang::get('mowork.bind_wechat')}}</div>
</div>
 
<div class="form-group input-group center-block text-center">
<a class="btn btn-md text-center" href="/skipover">{{Lang::get('mowork.skipover_band')}}</a>
</div>

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

  
  function wechatAuthAndBand(){
	  window.location.href = "http://www.mowork.cn/weixin/auth-band.php?b={{$uid}}";
  }
  </script>
 
@stop