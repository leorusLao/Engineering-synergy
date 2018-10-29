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
    <div class="text-center margin-t20 margin-b20">{{Lang::get('mowork.photo_invitation')}}</div>
	<div class="text-center" id="qrcode"></div>
	<script type="text/javascript">

		$('#qrcode').qrcode({
		    // render method: 'canvas', 'image' or 'div'
		    render: 'image',

		    // version range somewhere in 1 .. 40
		    minVersion: 1,
		    maxVersion: 40,

		    // error correction level: 'L', 'M', 'Q' or 'H'
		    ecLevel: 'L',

		    // offset in pixel if drawn onto existing canvas
		    left: 0,
		    top: 0,

		    // size in pixel
		    size: 200,

		    // code color or image element
		    fill: '#000',

		    // background color or image element, null for transparent background
		    background: null,

		    // content
		 	text: "http://weixin.mowork.cn/#/regist?uid={{$row->uid}}&username={{$row->username}}&companyId={{$row->company_id}}&companyName={{$row->company_name}}&departmentId=11&departmentName=总裁办&invitationType=1",
			
		    // corner radius relative to module width: 0.0 .. 0.5
		    radius: 0,

		    // quiet zone in modules
		    quiet: 0,

		    // modes
		    // 0: normal
		    // 1: label strip
		    // 2: label box
		    // 3: image strip
		    // 4: image box
		    mode: 2,

		    mSize: 0.1,
		    mPosX: 0.5,
		    mPosY: 0.5,

		    label: '',
		    fontname: 'sans',
		    fontcolor: '#f00',

		    image: null
		})
                    				  
	</script>
	<div class="text-center margin-t20">{{Lang::get('mowork.scan_invitation')}}</div>
 

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