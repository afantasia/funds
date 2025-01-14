<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h1>차트보기</h1>
            </div>
        </div>
    </div>
    <div class="card-body">
        <canvas id="recentChartCtx"></canvas>

    </div>
</div>
<script>
    $(document).ready(function(){
        // 랜덤 색상 생성 함수
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
        axios.post("/stock/recentHistory",{}).then((result)=> {
            var ctDatas=result.data.datas;
            // Chart.js 초기화
            new Chart(document.getElementById("recentChartCtx"), {
                type: "line",
                data: {
                    datasets: ctDatas, // 변환 없이 바로 사용
                },
            });
        });
    })
</script>
