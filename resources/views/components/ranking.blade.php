<div class="card" id="rankingInfo">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>랭킹</h1>
            <span class="badge bg-secondary" id="rankingPlayerCount">0명</span>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover" id="ranking-table">
            <thead>
                <tr>
                    <th class="text-center" style="width:60px">순위</th>
                    <th>이름</th>
                    <th class="text-end">보유현금</th>
                    <th class="text-end">주식평가</th>
                    <th class="text-end">총 자산</th>
                </tr>
            </thead>
            <tbody id="ranking-body"></tbody>
        </table>
        <div class="text-center py-3 d-none" id="rankingEmpty">
            랭킹 데이터가 없습니다.
        </div>
    </div>
</div>

<script type="text/template" id="ranking-template">
    <tr class="[[row_class]]">
        <td class="text-center"><strong>[[rank]]</strong></td>
        <td>[[name]]</td>
        <td class="text-end">[[cash]]</td>
        <td class="text-end">[[stock_value]]</td>
        <td class="text-end fw-bold">[[total_asset]]</td>
    </tr>
</script>

<script>
$(document).ready(function(){
    axios.get("/stock/ranking").then(function(res){
        if(res.data.code !== "0000" || !res.data.datas || !res.data.datas.length) {
            $("#rankingEmpty").removeClass("d-none");
            $("#ranking-table").addClass("d-none");
            return;
        }
        var template = $("#ranking-template").html();
        var $tbody = $("#ranking-body");
        var loginId = $(".navbar").attr("loginid");
        res.data.datas.forEach(function(item, idx){
            var isMe = loginId && loginId == item.user_id;
            $tbody.append(replaceTemplate(template, {
                rank: idx + 1,
                name: item.name + (isMe ? ' (나)' : ''),
                cash: dpPrice(item.cash),
                stock_value: dpPrice(item.stock_value),
                total_asset: dpPrice(item.total_asset),
                row_class: isMe ? 'table-warning' : '',
            }));
        });
        $("#rankingPlayerCount").text(res.data.datas.length + "명");
    }).catch(function(){
        $("#rankingEmpty").removeClass("d-none").text("랭킹을 불러오지 못했습니다.");
        $("#ranking-table").addClass("d-none");
    });
});
</script>
