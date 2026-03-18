<div class="card"  id="assetInfo">
    <div class="card-header">
        <div>
            <div>
                <h1>내재산보기</h1>
            </div>
            <div class="d-flex justify-content-between">
                <div>보유 금액 : <b class="cashPrice">444444</b></div>
                <div>총 보유 금액 : <b class="fundsPrice">688888</b></div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h4>내 보유 자산</h4>
        <h4>자산 비율</h4>
        <div>
            <canvas id="properyCtx"></canvas>
        </div>
        @auth
        @endauth
    </div>
    <div class="card-footer text-end">
        @auth
        <style>
            @keyframes nuke-pulse {
                0%, 100% { box-shadow: 0 0 8px rgba(220,53,69,.5); }
                50% { box-shadow: 0 0 20px rgba(220,53,69,.9), 0 0 40px rgba(255,0,0,.4); }
            }
            .btn-nuke {
                animation: nuke-pulse 1.5s ease-in-out infinite;
                border: 2px solid #dc3545;
                text-transform: uppercase;
                font-weight: 700;
                letter-spacing: 1px;
            }
            .btn-nuke:hover {
                animation: none;
                box-shadow: 0 0 30px rgba(255,0,0,.8), 0 0 60px rgba(220,53,69,.5);
                transform: scale(1.05);
                transition: all .2s;
            }
        </style>
        <button type="button" class="btn btn-danger btn-sm btn-nuke" onclick="showModal('resetModal')">
            <i class="xi-warning"></i> MAPO EXPRESS <i class="xi-warning"></i>
        </button>
        @endauth
    </div>
</div>

<script>
    $(document).ready(function(){
       axios.post("/user/getMyAsset",{}).then((result)=>{
           $datas=result.data.datas;
           var chartData=collect([]);


           let funds = collect($datas.funds)
               .groupBy('stock_id')
               .map((group) => {
                   const first = group.first(); // 동일한 stock_id 중 첫 번째 데이터를 사용
                   const nowAssetSum = group.sum('now_asset'); // now_asset 합산
                   return {
                       ...first,
                       now_asset: nowAssetSum, // 합산된 값으로 대체
                   };
               })
               .values()
               .all();

            // 데이터를 차례로 병합
           chartData = chartData.merge([$datas.cash]); // $datas.cash 추가
           chartData = chartData.merge(funds); // $datas.funds 추가
           $("#assetInfo .cashPrice").text(dpPrice( $datas.cash.now_asset ));
           $("#assetInfo .fundsPrice").text(dpPrice( collect(funds).sum("now_asset") + eval($datas.cash.now_asset) ));
           const ctx = document.getElementById('properyCtx');
           const data = {
               labels: chartData.pluck("stock_name").toArray(),
               datasets: [{
                   data: chartData.pluck("now_asset").toArray(),
                   hoverOffset: 4
               }]
           };
           const config = {
               type: 'pie',
               data: data,
           };
           new Chart(ctx, config);

       });


    });


</script>
