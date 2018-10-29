@extends('front-base')

@section('css.append')
{{ Html::style('/asset/css/app.css') }}
{{ Html::script('/asset/js/jquery-1.11.3.min.js') }}
{{ Html::script('/asset/js/jquery-qrcode-0.14.0.min.js') }}
 
@stop
 
@section('main-body')

@if(Session::has('result'))
	 <div class="alert alert-danger">
          {{Session::get('result')}}
     </div>
@endif
 

<div class="col-xs-12 col-sm-4 col-sm-offset-4">
    <div class="text-center"><h4>{{$row->company_name}}总裁办{{$row->username}}{{Lang::get('mowork.invite_you')}}</h4></div>

	<div class="text-center margin-t20" id="qrcode">
    <a href="http://weixin.mowork.cn/#/regist?uid={{$row->uid}}&username={{$row->username}}&companyId={{$row->company_id}}&companyName={{$row->company_name}}&departmentId=11&departmentName=总裁办&invitationType=1">
    {{$row->company_name}}总裁办{{$row->username}}{{Lang::get('mowork.invite_you')}}
    </a>
    </div>
	 
 

</div>
<div class="clearfix" style="margin-bottom:30px"></div>
@stop 
@section('footer.append')
<script type="text/javascript">
$(function(){

    $('#me1').addClass('active');
  
});
</script>
  
@stop