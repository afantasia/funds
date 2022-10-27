<script src="/js/app.js"></script>

@auth
    <a href="{!! route('logout') !!}">로그아웃</a>
@else
    <a href="{{route('login')}}">로그인</a>
@endauth
<table id="dataTable">
    <thead>
    <tr>
        <th>11</th>
        <th><div type="button" bind="addrow" param-key1="a" param-key2="3" param3-key1="c" >ddd</div></th>
    </tr>
    </thead>
    <tbody>
    <tr><td>dasdasdasasd</td><td><div type="button" bind="b"  param-key3="das" parm>ddd</div></td></tr>

    </tbody>
</table>