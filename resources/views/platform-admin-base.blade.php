@include('backend-head')

<body class="skin-josh">
@include('platform-admin-header')

<div class="wrapper row-offcanvas row-offcanvas-left">
@include('platform-admin-sidebar')
<aside class="right-side">
<!-- Main content -->
   <section class="content-header">
 
     <h3 class="text-center text-warning">MoWork 平台系统管理系统</h3>
  
				@if(isset($cookieTrail))
					<div class="nav_title">{!! $cookieTrail !!}</div>
				@endif 
				 
				<ol class="breadcrumb">
					<li class="active">
					<a href="/pfadmin/home"> <i class="livicon" data-name="home" data-size="14" data-color="#333" data-hovercolor="#333"></i>
							{{Lang::get('mowork.dashboard')}}
					</a>
					</li>
				</ol>
	</section>
	<section class="content">
		@yield('content')
	</section>
		   
  </aside>
</div>
  	 
<script src="/asset/js/dashboard.js" type="text/javascript"></script>
@yield('footer.append')
</body>
</html>
