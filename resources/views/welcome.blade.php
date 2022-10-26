@auth
    <a href="{!! route('logout') !!}">로그아웃</a>
@else
    <a href="{{route('login')}}">로그인</a>
@endauth