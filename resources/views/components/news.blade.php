<style>
    .tooltip.custom-tooltip {
        max-width: 600px; /* 너비 조정 */
        width: auto; /* 필요 시 고정 너비 */
    }

    .tooltip-inner {
        font-size: 14px; /* 폰트 크기 조정 */
        padding: 10px; /* 내부 여백 조정 */
    }
</style>

<div class="card">
    <div class="card-header"><h1>뉴스</h1></div>
    <div class="card-body">
        <table class="tab-content" width="100%" id="news-table">
            <thead>
                <tr>
                    <th>타이틀</th>
                    <th>날짜</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        axios.get("/stock/getNews").then((result) => {
            $("#news-table [data-bs-toggle='tooltip']").tooltip('dispose').tooltip();

            // 템플릿 가져오기
            const template = $("#news-template").html();

            // tbody 요소 참조
            const $tbody = $("#news-table tbody");

            // 데이터 치환 후 추가
            result.data.forEach((data) => {
                data.type_text = data.type== 'PLUS' ? '긍정적' : '부정적';
                const rowHtml = replaceTemplate(template, data);
                $tbody.append(rowHtml);
            });
            // 새롭게 추가된 요소에 대해 Bootstrap 툴팁 재초기화
            $("#news-table [data-bs-toggle='tooltip']").tooltip();
        });

    });

</script>


<script type="text/template" id="news-template">
    <tr>
        <td>
            <a href="#"
               class="custom-tooltip"
               data-bs-toggle="tooltip"
               data-bs-html="true"
               data-bs-title="[[title]] 가 되었습니다. ㅈ문가들은 [[name]]사 주식의 [[type_text]]인 영향을 끼칠거라 보도햇습니다"
            >[[title]]</a>
        </td>
        <td>
            [[created_at]]
        </td>
    </tr>
</script>
