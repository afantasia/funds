<!-- Modal -->
<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">매수 요청</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="buyForm">
                    <div class="mb-3">
                        <label class="form-label">회사선택</label>
                        <select name="stock_id" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">구매수량</label>
                        <input type="number" class="form-control" placeholder="수량을 입력해주세요" value="1" name="buy_count">
                    </div>
                    <div class="mb-3">
                        <div>내잔액 : <span class="myPoint"></span> </div>
                        <div>총매수금액 :  <span class="buyAmount"></span> </div>
                        <div>매수 후 잔액 :  <span class="calcAmount"></span> </div>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-submit" >매수가즈아!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    function getCompanys() {
        $("#buyForm select[name='stock_id']").append(`<option value="">선택하세요</option>`);
        axios.get("/stock/getCompany").then((result)=>{
            result.data.forEach(function(data,index){
                $("#buyForm select[name='stock_id']").append(`<option value="${data.id}" data-cost="${data.cost}">${data.name} [P ${data.cost}]</option>`);
            });
        });
    }
    getCompanys();

    function submitBuy() {
        const formData = $("#buyForm").serialize(); // 폼 데이터를 직렬화
        axios.post("/user/buy",formData).then((result)=>{
            if(result.data.code==0000){
                alert('매수완료');
                window.location.reload();
            }else{
                alert(result.data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // 에러 처리
        });
    }

    $("#buyModal button.btn-submit").on("click",function(){
        submitBuy();
    });
    $("#buyForm [name='buy_count'] , #buyForm [name='stock_id']").on("change",function(){
        var point = {{ isset($now_amount) ? $now_amount : 0 }};
        $("#buyForm .myPoint").text(dpPrice(point));
        $cost = $("#buyForm [name='stock_id'] option:selected").data("cost");
        $("#buyForm .buyAmount").text(dpPrice ($cost * $("#buyForm [name='buy_count']").val()));
        $("#buyForm .calcAmount").text( dpPrice( point - ( $cost * $("#buyForm [name='buy_count']").val() ) ) );
    })


})


</script>
