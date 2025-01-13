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
                        <label class="form-label">주식선택</label>
                        <select name="inven_id" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <div>내잔액 : <span class="myPoint"></span> </div>
                        <div>이전매수단가 :  <span class="buyAmount"></span> / 매수합 : <span class="calcBuyAmount"></span> </div>
                        <div>현재매도단가 :  <span class="sellAmount"></span> / 매도합 : <span class="calcSellAmount"></span> </div>
                        <div>손익단가 :  <span class="calcAmount"></span> / 손익합 :  <span class="calcTotAmount"></span></div>
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
    function getinventory() {
        $("#sellForm select[name='inven_id']").append(`<option value="">선택하세요</option>`);

        axios.get("/user/inventory").then((result)=>{
            result.data.datas.forEach(function(data,index){
                $("#sellForm select[name='inven_id']").append(`<option value="${data.id}" data-amount="${data.amount}" data-cost="${data.cost}" data-now-cost="${data.now_cost}">${data.company_name} ${data.amount}주</option>`);
            });
        });
    }
    getinventory();
    function submitSell() {
        const formData = $("#sellForm").serialize(); // 폼 데이터를 직렬화
        axios.post("/user/sell",formData).then((result)=>{
            alert('매도완료');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            // 에러 처리
        });
    }

    $("#sellModal button.btn-submit").on("click",function(){
        submitSell();
    });
    $("#sellForm [name='inven_id']").on("change",function(){
        var point = {{ isset($now_amount) ? $now_amount : 0 }};
        $("#sellForm .myPoint").text(point);
        $cost = $("#sellForm [name='inven_id'] option:selected").data("cost");
        $nowcost = $("#sellForm [name='inven_id'] option:selected").data("now-cost");
        $amount = $("#sellForm [name='inven_id'] option:selected").data("amount");
        $("#sellForm .buyAmount").text(dpPrice($cost));
        $("#sellForm .sellAmount").text(dpPrice($nowcost));

        $("#sellForm .calcBuyAmount").text(dpPrice($cost * $amount));
        $("#sellForm .calcSellAmount").text(dpPrice($nowcost * $amount));

        $("#sellForm .calcAmount").text(dpPrice(eval($nowcost) - eval($cost)));
        $("#sellForm .calcTotAmount").text(dpPrice( (eval($nowcost) - eval($cost)) * $amount ));
        $("#sellForm .totalAmount").text( dpPrice(point + ($nowcost*$cost)) );

    })


})


</script>
