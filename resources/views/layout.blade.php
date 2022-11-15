<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ㅔ?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="/css/app.css?id={!! date("His") !!}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body >
<div class="wrap" id="app">

    <div id="app-header">
        @auth
            <a href="{!! route('login') !!}" class="btn btn-primary" >로그아웃</a>
        @else
            <a class="btn btn-primary" href="{!! route('login') !!}">로그인</a>
        @endauth
    </div>
    <div id="app-body">
        <app-body></app-body>
    </div>
</div>
</body>
<script src="/js/app.js?id={!! date("His") !!}"></script>
</html>
