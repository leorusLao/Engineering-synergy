<div id="sidebar">
						<div class="inner">

							<!-- Search -->
								<section id="search" class="alt">
									<form method="post" action="/dashboard/search" id="searchform">
										  
									 <div class="input-group">
 										     
  											<input type="text" class="form-control">
  											<span class="input-group-btn">
    										<div class="btn btn-default" onclick="$('#searchform').submit()">{{Lang::get('mowork.search')}}</div>
  											</span>
									 </div>
									</form>
								</section>

							<!-- Menu -->
								<nav id="menu">
									 
									<ul>
										<li><a href="/">{{Lang::get('mowork.home')}}</a></li>
									 
										<li>
											<span class="opener" id='userinfo'>{{Lang::get('mowork.user_info')}}</span>
											<ul>
												<li class="active" id='changepassword'><a href="/dashboard/change-password">{{Lang::get('mowork.setup')}}{{Lang::get('mowork.change_password')}}</a></li>
												<li><a href="/dashboard/personal-profile">{{Lang::get('mowork.update')}}{{Lang::get('mowork.personal_info')}}</a></li>
												<li><a href="/dashboard/company-profile">{{Lang::get('mowork.company_profile')}}</a></li>
												<li><a href="/dashboard/purchase-service">{{Lang::get('mowork.purchase_service')}}</a></li>
												<li><a href="/dashboard/order-history">{{Lang::get('mowork.order_history')}}</a></li>
												<li><a href="/dashboard/logout">{{Lang::get('mowork.logout')}}</a></li>
												 
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.workboard')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.plan_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.delayed_plan')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.delayed_openissue')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.work_log')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.my_gadget')}}</a></li>
												 
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.project_management')}}</span>
											<ul>
												<li><a href="/dashboard/upper-project">{{Lang::get('mowork.upper_project')}}</a></li>
												<li><a href="/dashboard/setup-project">{{Lang::get('mowork.project_setup')}}</a></li>
												<li><a href="/dashboard/approve-project">{{Lang::get('mowork.project_approval')}}</a></li>
												<li><a href="/dashboard/list-project">{{Lang::get('mowork.project_list')}}</a></li>
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.plan_control')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.plan_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.plan_approval')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.department_plan')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.plan_node_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.plan_progress_input')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.plan_control_template')}}</a></li>
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.openissue')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.openissue_input')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_approval')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_list')}}</a></li>
												 
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.stats_report')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.current_delayed_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.project_delayed_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_delayed_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_project_list')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.plan_control_scan')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.openissue_scan')}}</a></li> 
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.user_management')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.user_role_allocation')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.user_role_management')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.user_role_resource_table')}}</a></li>
											</ul>
										</li>
										<li>
											<span class="opener">{{Lang::get('mowork.knowledge_management')}}</span>
											<ul>
												<li><a href="#">{{Lang::get('mowork.nation_standard')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.platform_template')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.industry_template')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.company_tempalte')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.company_file')}}</a></li>
												<li><a href="#">{{Lang::get('mowork.gadget_management')}}</a></li> 
											</ul>
										</li>
										<li>
											<span class="opener" id="config">{{Lang::get('mowork.system_configuration')}}</span>
											<ul>
												<li id='region'><a href="/dashboard/admin-region">{{Lang::get('mowork.region_management')}}</a></li>
												<li><a href="/dashboard/measurement-unit">{{Lang::get('mowork.gauge_management')}}</a></li>
												<li><a href="/dashboard/currency">{{Lang::get('mowork.currency_setup')}}</a></li>
												<li><a href="/dashboard/supplier">{{Lang::get('mowork.supply_managemet')}}</a></li>
												<li><a href="/dashboard/other-setup">{{Lang::get('mowork.! Session::has('userId')')}}</a></li>
												<li><a href="/dashboard/project-config">{{Lang::get('mowork.project_configuration')}}</a></li>
												<li><a href="/dashboard/openissue-config">{{Lang::get('mowork.openissue_configuration')}}</a></li>
												<li><a href="/dashboard/company-setup">{{Lang::get('mowork.company_management')}}</a></li>
												<li><a href="/dashboard/employee-setup">{{Lang::get('mowork.employee_management')}}</a></li>
												<li><a href="/dashboard/calendar-setup">{{Lang::get('mowork.calendar_setup')}}</a></li>
												<li><a href="/dashboard/plan-scan-config">{{Lang::get('mowork.plan_scan_configuration')}}</a></li>
												<li><a href="/dashboard/openissue-scan-config">{{Lang::get('mowork.openissue_scan_configuration')}}</a></li>
												<li><a href="/dashboard/data-linking">{{Lang::get('mowork.system_communcication_api')}}</a></li> 
											</ul>
										</li>
									 
										<li><a href="/dashboard/logout">{{Lang::get('mowork.logout')}}</a></li>
									</ul>
								</nav>
 
							 

						</div>
					</div>

		 