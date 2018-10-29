<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
@yield('page-exipiration')
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    @if(isset($pageTitle))
    <title>{{ $pageTitle }} - {{Lang::get('mowork.site_name')}}</title>
    @else
     <title>{{Lang::get('mowork.site_name')}}</title>
    @endif
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link href="/asset/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/asset/css/app.css" rel="stylesheet" type="text/css" />
    <!-- end of global css -->
    <script src="/asset/js/app.js" type="text/javascript"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    @yield('css.append')
    <!--end of page level css-->
</head>