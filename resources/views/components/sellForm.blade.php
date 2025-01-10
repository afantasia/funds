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
                        <label class="form-label">회사선택</label>
                        <select name="inven_id" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-submit" >제출</button>
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
                $("#sellForm select[name='inven_id']").append(`<option value="${data.id}">${data.company_name} ${data.amount}주</option>`);
            });
        });
    }
    getinventory();
    function submitSell() {
        const formData = $("#sellForm").serialize(); // 폼 데이터를 직렬화
        axios.post("/user/sell",formData).then((result)=>{
            console.log(result.data);
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


})


</script>
