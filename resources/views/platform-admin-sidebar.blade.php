<!-- Left side column. contains the logo and sidebar -->

<aside class="left-side sidebar-offcanvas">
	<section class="sidebar ">
		<div class="page-sidebar sidebar-nav dropdown">
   	 		<ul id="menu" class="page-sidebar-menu">
				<li id='me0'><a href="/pfadmin/home"> <span class="title">{{Lang::get('mowork.dashboard')}}</span>
				</a></li>
        				
				<li>
 				   <div id='me8' class="have text-center"><a href="#"> 
						<span class="title">{{Lang::get('mowork.node_template_management')}}</span> <span class="fa arrow"></span>
					</a>
				   </div>
		
				   <ul class="sub-menu">
  	  	        		     <li><a href="/pfadmin-su/node-type">{{Lang::get('mowork.node_type')}}</a></li>
		                     <li><a href="/pfadmin-su/node-list">{{Lang::get('mowork.plan_node_list')}}</a></li>
		                     
	 				</ul>
				</li>
               
				<li>
				<div id='me9' class="have text-center"><a href="#"><span class="title">{{Lang::get('mowork.subsidiary')}}</span> <span class="fa arrow"></span>
					</a>
				</div>
					<ul class="sub-menu">
						<li><a href="/pfadmin/company-list"> <i class="fa fa-angle-double-right"></i>
								{{Lang::get('mowork.company_list')}}
						</a></li>
						<li><a href="/pfadmin/new-arrival-approval"> <i class="fa fa-angle-double-right"></i>
								{{Lang::get('mowork.company_bu_approval')}}
						</a></li>
						 
					</ul>
				</li>
				 
				<li>
				<div class="text-center"><a href="/dashboard/logout">
						<span class="title">{{Lang::get('mowork.logout')}}</span> <span class="fa arrow"></span>
				    </a>
				</div>
				</li>

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
