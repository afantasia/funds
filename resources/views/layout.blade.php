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
<div class="wrap">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" @auth loginid="{!! Auth::id() !!}" @endauth>
            <navi/>
        </nav>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-6">
                    <news/>
                </div>
                <div class="col-6">
                    <charts/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <trades/>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ mix('js/app.js') }}"></script>
</html>