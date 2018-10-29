<!-- Left side column. contains the logo and sidebar -->

<aside class="left-side sidebar-offcanvas">
	<section class="sidebar ">
		<div class="page-sidebar sidebar-nav dropdown">
   	 		<ul id="menu" class="page-sidebar-menu">
				<li id='me0'><a href="/dashboard"> <i class="livicon"
						data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA"
						data-loop="true"></i> <span class="title">{{Lang::get('mowork.dashboard')}}</span>
				</a></li>
 
				<li>
				<div id='me1' class="have">
					<a href="#"> <i class="livicon" data-name="medal" data-size="18" data-c="#00bc8c" data-hc="#00bc8c" data-loop="true"></i> <span
						class="title">{{Lang::get('mowork.user_info')}}</span> <span class="fa arrow"></span>
					</a>
				</div>
					<ul class="sub-menu">
					    <!-- 300 用户信息：不做限制-->
					    <li><a href="/dashboard/personal-profile"> <i class="fa fa-angle-double-right"></i>
							{{Lang::get('mowork.personal_info')}}
							</a>
						</li>
						<li><a href="/dashboard/change-password"> <i class="fa fa-angle-double-right"></i>
							{{Lang::get('mowork.setup')}}{{Lang::get('mowork.change_password')}}
							</a>
						</li>
 					    <li><a href="/dashboard/bind-wechat"> <i class="fa fa-angle-double-right"></i>
							{{Lang::get('mowork.bind_wechat')}}
							</a>
						</li>
						@if(!isset(Session::get('USERINFO')->companyId) || (isset(Session::get('USERINFO')->userRole) && Session::get('USERINFO')->userRole ==20) )
							<li><a href="/dashboard/my-company"> <i class="fa fa-angle-double-right"></i>
								{{Lang::get('mowork.my_company')}}
						        </a>
						    </li>
						@endif
						@if(isset(Session::get('USERINFO')->companyId))
							<li><a href="/dashboard/enter-worksite"> <i class="fa fa-angle-double-right"></i>
								{{Lang::get('mowork.companysite_entry')}}
						        </a>
						    </li>
						@endif
						<li><a href="/dashboard/share-friend"> <i class="fa fa-angle-double-right"></i>
                             {{Lang::get('mowork.share_follow')}}
                             </a>
                        </li>
					 
					</ul>
				</li>
				<li>
                <div><a href="/dashboard/logout"> <i class="livicon" data-name="minus-alt"
						data-c="#FF6F6C" data-hc="#F89A14" data-size="18" data-loop="true"></i>
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
