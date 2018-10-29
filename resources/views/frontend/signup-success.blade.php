@extends('front-base')
  
@section('main-body')
<div class="container" >
 
<div class="row margin-t50">
<div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
<div class="panel panel-default">
<div class="panel-heading">
 
<h3 class="panel-title text-center margin-t20">{{Lang::get('mowork.signup')}}</h3>
</div>
<div class="panel-body">
 		      
		<fieldset>
	@if (Session::has('result'))
        <div class="alert alert-danger text-center">
           {{Session::get('result')}}
        </div>
    @endif  
		 
		<div class="form-group spacer-10">
				<a href="/login" class="btn btn-lg btn-info btn-block" style="color:#fafafa">{{Lang::get('mowork.now_login')}}</a>
		</div>
	 	 
		</fieldset>
</div>
</div>
</div>
</div>
 
</div>
@stop

@section('footer.append')
	<script type="text/javascript" src="/asset/js/TweenLite.min.js">
    <script type="text/javascript">
	 $(function() {
	 	$(document).mousemove(function(event) {
	 		TweenLite.to($('body'), .5, {css:{'background-position':parseInt(event.pageX/8) + "px "+parseInt(event.pageY/12)+"px, "+parseInt(event.pageX/15)+"px "+parseInt(event.pageY/15)+"px, "+parseInt(event.pageX/30)+"px "+parseInt(event.pageY/30)+"px"}});
	 	});
	 });
	 	</script>
@stop
