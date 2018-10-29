@extends('backend-base') 

@section('css.append')

@stop

@section('content')
   
	<div class="col-xs-12 col-sm-8 col-sm-offset-2">
	@if($result == 'success')
     <div class="text-center"><a class="text-center" href="/dashboard/personal-profile">{{Lang::get('mowork.bind_success')}},
    {{Lang::get('mowork.read')}}</a></div>
    @else 
	 <div class="text-center text-danger"><b>{{Lang::get('mowork.bind_failure')}}</b></div>		 
	@endif
	</div>


@stop
 