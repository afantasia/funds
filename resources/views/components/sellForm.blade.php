<!-- Modal -->
<div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellModalLabel">매도 요청</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sellForm">
                    <div class="mb-3">
                        <label class="form-label">종목 선택</label>
                        <select name="stock_id" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">매도 수량</label>
                        <input type="number" name="sell_count" class="form-control" min="1" value="1" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <div>내잔액 : <span class="myPoint"></span> </div>
                        <div>평균매수단가 :  <span class="buyAmount"></span> / 매수합 : <span class="calcBuyAmount"></span> </div>
                        <div>현재매도단가 :  <span class="sellAmount"></span> / 매도합 : <span class="calcSellAmount"></span> </div>
                        <div>손익단가 :  <span class="calcAmount"></span> / 손익합 : <span class="calcTotAmount"></span></div>
                        <div>매도 후 잔액 :  <span class="totalAmount"></span> </div>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-submit" >매도</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    function buildGroupedInventoryRows(datas) {
        return collect(datas)
            .groupBy('stock_id')
            .map(function(group){
                var first = group.first();
                var totalAmount = group.sum('amount');
                var buyTotal = group.toArray().reduce(function(sum, item){
                    return sum + (item.cost * item.amount);
                }, 0);
                var avgCost = totalAmount > 0 ? buyTotal / totalAmount : 0;
                return {
                    stock_id: first.stock_id,
                    company_name: first.company_name,
                    total_amount: totalAmount,
                    avg_cost: avgCost,
                    now_cost: first.now_cost
                };
            })
            .values()
            .all();
    }

    function loadSellInventorySelect() {
        if (typeof invalidateInventoryCache === 'function') {
            invalidateInventoryCache();
        }
        $("#sellForm select[name='stock_id']").empty().append('<option value="">선택하세요</option>');
        getInventoryData().then(function(result){
            if (!result.data || result.data.code !== '0000' || !result.data.datas || !result.data.datas.length) {
                return;
            }
            var rows = buildGroupedInventoryRows(result.data.datas);
            rows.forEach(function(row){
                $("#sellForm select[name='stock_id']").append(
                    '<option value="' + row.stock_id + '" data-total="' + row.total_amount + '" data-avg-cost="' + row.avg_cost + '" data-now-cost="' + row.now_cost + '">' +
                    row.company_name + ' (' + row.total_amount + '주)</option>'
                );
            });
        });
    }

    loadSellInventorySelect();

    $("#sellModal").on("show.bs.modal", function(){
        loadSellInventorySelect();
    });

    function updateSellPreview() {
        var point = {{ isset($now_amount) ? $now_amount : 0 }};
        $("#sellForm .myPoint").text(dpPrice(point));
        var $opt = $("#sellForm [name='stock_id'] option:selected");
        if (!$opt.val()) {
            $("#sellForm .buyAmount, #sellForm .sellAmount, #sellForm .calcBuyAmount, #sellForm .calcSellAmount, #sellForm .calcAmount, #sellForm .calcTotAmount, #sellForm .totalAmount").text("-");
            return;
        }
        var avgCost = parseFloat($opt.data("avg-cost")) || 0;
        var nowCost = parseFloat($opt.data("now-cost")) || 0;
        var maxTotal = parseInt($opt.data("total"), 10) || 1;
        var sellCount = parseInt($("#sellForm [name='sell_count']").val(), 10);
        if (isNaN(sellCount) || sellCount < 1) sellCount = 1;
        if (sellCount > maxTotal) {
            sellCount = maxTotal;
            $("#sellForm [name='sell_count']").val(sellCount);
        }
        $("#sellForm [name='sell_count']").attr({ min: 1, max: maxTotal });

        $("#sellForm .buyAmount").text(dpPrice(avgCost));
        $("#sellForm .sellAmount").text(dpPrice(nowCost));
        $("#sellForm .calcBuyAmount").text(dpPrice(avgCost * sellCount));
        $("#sellForm .calcSellAmount").text(dpPrice(nowCost * sellCount));
        $("#sellForm .calcAmount").text(dpPrice(nowCost - avgCost));
        $("#sellForm .calcTotAmount").text(dpPrice((nowCost - avgCost) * sellCount));
        $("#sellForm .totalAmount").text(dpPrice(point + nowCost * sellCount));
    }

    $("#sellForm [name='stock_id']").on("change", function(){
        var $opt = $("#sellForm [name='stock_id'] option:selected");
        var maxTotal = parseInt($opt.data("total"), 10) || 1;
        if ($opt.val()) {
            $("#sellForm [name='sell_count']").attr({ min: 1, max: maxTotal }).val(maxTotal);
        }
        updateSellPreview();
    });

    $("#sellForm [name='sell_count']").on("input change", function(){
        updateSellPreview();
    });

    function submitSell() {
        var stockId = $("#sellForm [name='stock_id']").val();
        var sellCount = $("#sellForm [name='sell_count']").val();
        if (!stockId) {
            alert('종목을 선택하세요.');
            return;
        }
        var formData = $("#sellForm").serialize();
        axios.post("/user/sell", formData).then(function(result){
            if (result.data && result.data.code === '0003') {
                alert('매도완료');
                if (typeof invalidateInventoryCache === 'function') {
                    invalidateInventoryCache();
                }
                window.location.reload();
            } else {
                alert((result.data && result.data.message) ? result.data.message : '매도에 실패했습니다.');
            }
        }).catch(function(error){
            console.error('Error:', error);
            var msg = (error.response && error.response.data && error.response.data.message)
                ? error.response.data.message
                : '요청 처리 중 오류가 발생했습니다.';
            alert(msg);
        });
    }

    $("#sellModal button.btn-submit").on("click", function(){
        submitSell();
    });
});
</script>
