<div class="card" id="inventoryInfo">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>내 투자 내역</h1>
            <span class="badge bg-secondary" id="inventoryCount">0종목</span>
        </div>
    </div>
    <div class="card-body">
        @auth
        <table class="table table-hover" id="inventory-table">
            <thead>
                <tr>
                    <th>종목명</th>
                    <th class="text-end">보유수량</th>
                    <th class="text-end">평균매수가</th>
                    <th class="text-end">현재가</th>
                    <th class="text-end">매수총액</th>
                    <th class="text-end">평가총액</th>
                    <th class="text-end">손익금액</th>
                    <th class="text-end">수익률</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr class="table-active fw-bold">
                    <td colspan="4" class="text-center">합계</td>
                    <td class="text-end" id="totalBuyAmount">-</td>
                    <td class="text-end" id="totalEvalAmount">-</td>
                    <td class="text-end" id="totalProfitAmount">-</td>
                    <td class="text-end" id="totalProfitRate">-</td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center py-3 d-none" id="inventoryEmpty">
            보유 중인 종목이 없습니다.
        </div>
        @else
        <div class="text-center py-3 text-muted">
            로그인 후 이용 가능합니다.
        </div>
        @endauth
    </div>
</div>

<script type="text/template" id="inventory-template">
    <tr>
        <td><strong>[[company_name]]</strong></td>
        <td class="text-end">[[total_amount]]주</td>
        <td class="text-end">[[avg_cost]]</td>
        <td class="text-end">[[now_cost]]</td>
        <td class="text-end">[[buy_total]]</td>
        <td class="text-end">[[eval_total]]</td>
        <td class="text-end [[profit_class]]">[[profit_amount]]</td>
        <td class="text-end [[profit_class]]">[[profit_rate]]</td>
    </tr>
</script>

@auth
<script>
$(document).ready(function(){
    axios.get("/user/inventory").then(function(result){
        if(result.data.code !== "0000" || !result.data.datas.length) {
            $("#inventoryEmpty").removeClass("d-none");
            $("#inventory-table").addClass("d-none");
            return;
        }

        var grouped = collect(result.data.datas)
            .groupBy('stock_id')
            .map(function(group){
                var first = group.first();
                var totalAmount = group.sum('amount');
                var buyTotal = group.toArray().reduce(function(sum, item){
                    return sum + (item.cost * item.amount);
                }, 0);
                var avgCost = buyTotal / totalAmount;
                var nowCost = first.now_cost;
                var evalTotal = nowCost * totalAmount;
                var profitAmount = evalTotal - buyTotal;
                var profitRate = (profitAmount / buyTotal) * 100;

                return {
                    company_name: first.company_name,
                    stock_id: first.stock_id,
                    total_amount: totalAmount,
                    avg_cost: dpPrice(avgCost),
                    now_cost: dpPrice(nowCost),
                    buy_total: dpPrice(buyTotal),
                    eval_total: dpPrice(evalTotal),
                    profit_amount: (profitAmount >= 0 ? '+' : '') + dpPrice(profitAmount),
                    profit_rate: (profitRate >= 0 ? '+' : '') + profitRate.toFixed(2) + '%',
                    profit_class: profitAmount > 0 ? 'text-danger' : (profitAmount < 0 ? 'text-primary' : ''),
                    _raw_buy: buyTotal,
                    _raw_eval: evalTotal,
                    _raw_profit: profitAmount,
                };
            })
            .values()
            .all();

        var template = $("#inventory-template").html();
        var $tbody = $("#inventory-table tbody");

        grouped.forEach(function(data){
            $tbody.append(replaceTemplate(template, data));
        });

        $("#inventoryCount").text(grouped.length + "종목");

        var sumBuy = grouped.reduce(function(s, d){ return s + d._raw_buy; }, 0);
        var sumEval = grouped.reduce(function(s, d){ return s + d._raw_eval; }, 0);
        var sumProfit = sumEval - sumBuy;
        var sumRate = sumBuy > 0 ? (sumProfit / sumBuy) * 100 : 0;

        var profitClass = sumProfit > 0 ? 'text-danger' : (sumProfit < 0 ? 'text-primary' : '');

        $("#totalBuyAmount").text(dpPrice(sumBuy));
        $("#totalEvalAmount").text(dpPrice(sumEval));
        $("#totalProfitAmount").text((sumProfit >= 0 ? '+' : '') + dpPrice(sumProfit)).addClass(profitClass);
        $("#totalProfitRate").text((sumRate >= 0 ? '+' : '') + sumRate.toFixed(2) + '%').addClass(profitClass);
    });
});
</script>
@endauth
