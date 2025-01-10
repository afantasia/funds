<div class="container-fluid">
    <a class="navbar-brand">주식망겜</a>
    <div class="d-flex">
        <a class="btn btn-primary" href="@if(Auth::check()) logout @else login @endif"> @if(Auth::check()) 로그아웃 @else 로그인 @endif</a>
    </div>
</div>
