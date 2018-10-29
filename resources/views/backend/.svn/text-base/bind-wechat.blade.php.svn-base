@extends('backend-base') 

@section('css.append')

@stop

@section('content')
   
	<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			@if(isset($row) && empty($row->wechat))
			<h4 class="text-center">{{Lang::get('mowork.not_bind')}}</h4>
			<p class="text-center"><a class="btn btn-info"  href="http://www.mowork.cn/weixin/auth-bind.php?b={{$row->uid}}&cd={{$currentDomain}}&t={{csrf_token()}}" target="_blank">{{Lang::get('mowork.bind_now')}}</a></p>
	        @else 
	        <h4 class="text-center">{{Lang::get('mowork.binded')}}</h4>
	        @endif
	</div>


@stop