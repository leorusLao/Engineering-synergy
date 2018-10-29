<!-- Left side column. contains the logo and sidebar -->

<aside class="left-side sidebar-offcanvas">
	<section class="sidebar ">
		<div class="page-sidebar sidebar-nav dropdown">
   	 		<ul id="menu" class="page-sidebar-menu">

				@foreach($siderBarData as $kk => $v)

				<li>
					<div @if(count($v['children']) != 0) class="have" @endif>
						<a href="{{$v['name']}}">
							{!! htmlspecialchars_decode($v['icon']) !!}
							<span class="title">{{$v['display_name']}}</span>
							@if(count($v['children']) != 0)
								<span class="fa arrow"></span>
							@endif
						</a>
					</div>
					@if(count($v['children']) != 0)
						<ul class="sub-menu">
						    @foreach($v['children'] as $k => $val)
							<li @if(count($val['children']) != 0) class="dropdown-submenu" @endif>
								<a href="{{$val['name']}}">
									@if(count($val['children']) == 0)
									<i class="fa fa-angle-double-right"></i>
									@endif
	                            	{{$val['display_name']}}
	                            </a>
	                            @if(count($val['children']) != 0)
	                            <ul class="dropdown-menu">
	         						@foreach($val['children'] as $key => $value)
	         						<li>
	         							<a href="{{$value['name']}}">{{$value['display_name']}}</a>
	         						</li>
	         						@endforeach
	         					</ul>
	         					@endif
	                        </li>
	                        @endforeach
						</ul>
					@endif
				</li>


				@endforeach


			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</section>
	<!-- /.sidebar -->
</aside>

<script>
var $pathname = window.location.pathname;
$('ul.sub-menu li a').each(function(){
	if($(this).attr('href') == $pathname){
		$(this).parent('li').addClass('active');
		$(this).parent().parent('ul').css('display','block');
		$(this).parent().parent('ul').prev('div').addClass('active');
	}
})
</script>