<div class="card">
    <div class="card-header">
        <div>
            <div>
                <h1>내재산보기</h1>
            </div>
            <div class="d-flex justify-content-between">
                <div>보유 금액 : <b>444444</b></div>
                <div>총 보유 금액 : <b>688888</b></div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h4>내 보유 자산</h4>
        <h4>자산 비율</h4>
        <div>
            <canvas id="properyCtx"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    const ctx = document.getElementById('properyCtx');
    const data = {
        labels: [
            '주식1',
            '주식2',
            '주식3',
            '주식4',
            '재산금'
        ],
        datasets: [{
            data: [30874, 33330, 10300,43434,63455],
            hoverOffset: 4
        }]
    };
    const config = {
        type: 'doughnut',
        data: data,
    };
    new Chart(ctx, config);

</script>
