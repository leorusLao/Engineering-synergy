@include('backend-head')
<body class="skin-josh">
@include('backend-header')
<div class="wrapper row-offcanvas row-offcanvas-left">
    @include('left-sidebar')
    <aside class="right-side">
        <!-- Main content -->
        <section class="content-header">
            @if(!isset(Session::get('USERINFO')->buId))
                @if(Session::get('USERINFO')->companyId > 0)
                    <h4 class="text-center">
                        {{Session::get('USERINFO')->companyName}}
                    </h4>
                @else
                    <h4 class="text-center text-warning">
                        {{Lang::get('mowork.monk')}}
                    </h4>
                @endif
            @endif
            @if(isset($cookieTrail))
                <div class="nav_title">{!! $cookieTrail !!}</div>
            @endif
            <ol class="breadcrumb">
                <li class="active">
                    <a href="/dashboard"> <i class="livicon" data-name="home" data-size="14" data-color="#333"
                                             data-hovercolor="#333"></i>
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
<script type="text/javascript">
    function updateMessagStatus(msgid) {
        $.ajax({
            type: 'POST',
            url: "{{url('/dashboard/message-be-read')}}",
            data: {
                msgid: msgid,
                _token: "{{ csrf_token() }}"
            },
            dataType: "html",
            success: function (data) {
            },
            error: function (xhr, status, error) {
            },
        });
    }
</script>
@yield('footer.append')
</body>
</html>