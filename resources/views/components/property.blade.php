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
           $("#assetInfo .fundsPrice").text(dpPrice( collect(funds).sum("now_asset") ));
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
