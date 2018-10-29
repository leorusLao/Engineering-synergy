<header class="header">
    <a href="/" class="logo"> <img src="/asset/img/common/logo.png" height=65 alt="MoWork-Logo"></a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img
                                src="{{Session::get('USERINFO')->avatar?Session::get('USERINFO')->avatar:'/asset/images/avatar.png'}}"
                                width="35"
                                class="img-circle img-responsive pull-left" height="35">
                        <div class="riot">
                            <div>{{Session::get('USERINFO')->username}}<span> <i class="caret"></i></span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="/dashboard/personal-profile">
                                {{Lang::get('mowork.profile')}}
                            </a>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div>
                                <a href="/dashboard/logout">
                                    {{Lang::get('mowork.logout')}}
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu" style="border-right:0px;"><a href="#"
                                                                                     class="dropdown-toggle"
                                                                                     data-toggle="dropdown"> <i
                                class="livicon"
                                data-name="comment" data-loop="true" data-color="#eee"
                                data-hovercolor="#e9573f" data-size="28" style="position:relative;top:5px;"></i> <span
                                class="label label-warning">{{Session::get('NOTICOUNTS')}}</span>
                    </a>
                    <ul class=" notifications dropdown-menu">
                        <li class="dropdown-title">{{Lang::get('mowork.uhave')}} {{ Session::get('NOTICOUNTS')}} {{Lang::get('mowork.event_notification')}}</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @if(Session::has('Notifications'))
                                    @foreach(Session::get('Notifications') as $row)
                                        <li>
                                            <a href="{{$row->site_url}}"
                                               @if($row->status==0) onclick="updateMessagStatus({{$row->id}});" @endif>
                                                <h5>{{$row->event_name}}</h5>
                                                <h6 class="text-center"><b>{{$row->subject}}</b></h6>
                                            </a>
                                            <small class="pull-right">
									<span
                                            class="livicon paddingright_10" data-n="timer" data-s="10">
									</span>
                                                {{substr($row->updated_at,2)}}
                                            </small>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                        <li class="footer"><a href="/dashboard/#">{{Lang::get('mowork.show_all')}}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>