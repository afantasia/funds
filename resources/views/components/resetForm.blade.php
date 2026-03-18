<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="resetModalLabel">마포대교 급행열차</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="xi-train" style="font-size: 3rem; color: var(--bs-danger);"></i>
                </div>
                <p class="text-center fw-bold">정말로 자산을 초기화하시겠습니까?</p>
                <ul class="text-muted small">
                    <li>보유 중인 모든 주식이 매도(반환) 처리됩니다.</li>
                    <li>거래 내역이 모두 삭제됩니다.</li>
                    <li>초기 자본금으로 다시 시작합니다.</li>
                </ul>
                <p class="text-danger text-center small fw-bold">이 작업은 되돌릴 수 없습니다.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-danger" id="btnResetConfirm">초기화 실행</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#btnResetConfirm").on("click", function(){
        var $btn = $(this);
        $btn.prop("disabled", true).text("처리 중...");
        axios.post("/user/reset", {})
            .then(function(result){
                if(result.data.code === "0000"){
                    notify("리셋 완료", result.data.msg);
                    setTimeout(function(){ window.location.reload(); }, 1000);
                } else {
                    notify("리셋 실패", result.data.msg);
                    $btn.prop("disabled", false).text("초기화 실행");
                }
            })
            .catch(function(){
                notify("오류", "요청 처리 중 오류가 발생했습니다.");
                $btn.prop("disabled", false).text("초기화 실행");
            });
    });
});
</script>
