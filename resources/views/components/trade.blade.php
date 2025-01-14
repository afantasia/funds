<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div><h1>내 거래영역</h1></div>
            <div>
                <button class="btn btn-primary" type="button"
                    onclick="showModal('buyModal')"
                >매입</button>
                /
                <button class="btn btn-primary" type="button"
                        onclick="showModal('sellModal')"
                >매도</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table" id="my-trade-table">
            <thead>
                <tr>
                    <th>제목</th>
                    <th>이전금액</th>
                    <th>증감금액</th>
                    <th>수수료</th>
                    <th>현재금액</th>
                    <th>일시</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        axios.get("/trade/getTradeHistory").then((result) => {
            $("#my-trade-table [data-bs-toggle='tooltip']").tooltip('dispose').tooltip();

            // 템플릿 가져오기
            const template = $("#my-trade-template").html();

            // tbody 요소 참조
            const $tbody = $("#my-trade-table tbody");

            // 데이터 치환 후 추가
            result.data.datas.forEach((data) => {
                data.fee_amount = dpPrice(data.fee_amount.toLocaleString());
                data.before_amount = dpPrice(data.before_amount.toLocaleString());
                data.calc_amount = dpPrice(data.calc_amount.toLocaleString());
                data.now_amount = dpPrice(data.now_amount.toLocaleString());
                const rowHtml = replaceTemplate(template, data);
                $tbody.append(rowHtml);
            });
            // 새롭게 추가된 요소에 대해 Bootstrap 툴팁 재초기화
            $("#my-trade-table [data-bs-toggle='tooltip']").tooltip();
        });

    });
</script>


<script type="text/template" id="my-trade-template">
    <tr>
        <td>[[title]]</td>
        <td>[[before_amount]]</td>
        <td>[[calc_amount]]</td>
        <td>[[fee_amount]]</td>
        <td>[[now_amount]]</td>
        <td>[[created_at]]</td>
    </tr>
</script>
