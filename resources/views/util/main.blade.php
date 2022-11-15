<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>ㅔ?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css" >
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script
            src="https://code.jquery.com/jquery-3.6.1.min.js"
            integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
            crossorigin="anonymous"></script>
</head>
<body >
    <form id="form" enctype="multipart/form-data" method="post" target="frAttachFiles" action="{{route("util.upload")}}" :onsubmit="disableForm()">
        @csrf
        <input type="file" name="pdfFile"  accept="application/pdf" required>

        <select name="fileType">
            <option value="pdf">pdf로받기</option>
            <option value="zip">zip파일로받기</option>
        </select>
        <select name="quality">
        @foreach(array_reverse(range(10,100,5)) as $value)
            <option value="{{$value}}">{!! $value !!}%</option>
        @endforeach
        </select>
        <button type="button" onclick="disableForm()" id="ddBtn">업로드하기</button>
    </form>
    <div id="resultArea">
        <div id="reseultText"></div>
    </div>
    <iframe name='frAttachFiles' id="frAttachFiles" style="display:none;border:1px solid red;width:100vw;height:30vw;"></iframe>
</body>
<Script>
    function disableForm(){
        $("#form")[0].submit();
        $("#form")[0].reset();
        $("#ddBtn").attr("disabled",'disabled')
        $("#reseultText").html("다운로드가 끝나면 새로고침을 해주세요")
    }
</Script>
</html>