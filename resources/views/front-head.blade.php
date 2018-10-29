<!DOCTYPE html>
<html lang="zh_cn" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">

<head>
<meta charset="UTF-8">
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />

<title> 
    @if(isset($pageTitle))
    {{ $pageTitle }} | {{Lang::get('mowork.site_name')}}
    @else
    {{Lang::get('mowork.site_name')}}
    @endif
</title>
<meta name="description" content="">
<meta name="keywords" content="">

<script src="/asset/js/jquery-1.11.3.min.js"></script>
<script src="/asset/js/bootstrap.min.js"></script>
<link href="/asset/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css"> 
<link href="/asset/css/style.css" type="text/css" rel="stylesheet" />
<link href="/asset/css/index.css" type="text/css" rel="stylesheet" />
<link href="/asset/css/font-awesome.min.css" type="text/css" rel="stylesheet" >
@yield('css.append')
</head>