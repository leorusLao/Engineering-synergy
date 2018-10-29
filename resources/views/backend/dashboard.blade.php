@extends('backend-base') 

@section('css.append')

@stop

@section('content')
   
	<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<h3 class="text-center margin-b30">{{Lang::get('mowork.site_name')}}</h3>
			<h4 class="text-center">{{Lang::get('mowork.app_developer')}}</h4>
			<h5 class="text-center">({{Lang::get('mowork.version')}})</h5>
    @if(isset($_SESSION['CHANGPASSWARNING']))
         <h4 class="text-center text-danger margin-t20">{{$_SESSION['CHANGPASSWARNING']}}</h4>
    @endif
     
    @if(!$passwordGuarded)
         
        <div class="text-center margin-t20"><a href="/dashboard/change-password"><b>{{Lang::get('mowork.guarded_note')}}</b></a></div>
         
    @endif
    </div>
@stop
@section('footer.append')

<script type="text/javascript">
$(function(){
    $('#me0').addClass('active');
});

</script>
@stop
