<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h1>차트 컨테이너</h1>
            </div>
            <div>
                <select onchange="getStockHistory();" id="companyLists" class="form-select"></select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart">
            <apexchart type="candlestick" height="350" id="chart"></apexchart>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    function getCompanyLists() {
        $companys = [];
        $("#companyLists").append(`<option value="">선택하세요</option>`);
        axios.get("/stock/getCompany").then((result)=>{
            result.data.forEach(function(data,index){
                $("#companyLists").append(`<option value="${data.id}">${data.name}</option>`);

            });
        });
    }

    function getStockHistory() {

        document.querySelector("#chart").innerHTML='';
        axios.get("/stock/getStockHistory/"+event.target.value).then((result)=> {
            var ctDatas=[];
            result.data.forEach(function(data,index){
                ctDatas.push({
                    'x':new Date(data.created_at),
                    'y':[data.max_amount,data.max_amount,data.min_amount,data.min_amount]
                });
            });
            console.log(result.data);
            var options = {
                series: [{
                    data:ctDatas,
                }],
                chart: {
                    type: 'candlestick',
                    height: 350
                },
                title: {
                    text: document.querySelector('#companyLists  > option:checked').text.trim()+' 차트내역서',
                    align: 'left'
                },
                xaxis: {
                    labels: {
                        formatter: function (value, timestamp) {
                            const xDate = new Date(value);

                            return xDate.getFullYear() + "-" +
                                ("00" + (xDate.getMonth() + 1)).slice(-2) + "-" +
                                ("00" + xDate.getDate()).slice(-2) + " " +
                                ("00" + xDate.getHours()).slice(-2) + ":" +
                                ("00" + xDate.getMinutes()).slice(-2)
                        },
                    }
                },
                yaxis: {
                    tooltip: {
                        enabled: true
                    },

                    labels: {
                        /**
                         * Allows users to apply a custom formatter function to yaxis labels.
                         *
                         * @param { String } value - The generated value of the y-axis tick
                         * @param { index } index of the tick / currently executing iteration in yaxis labels array
                         */
                        formatter: function(val, index) {
                            return val.toFixed(2);
                        },

                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });


    }
    $(document).ready(function(){
        getCompanyLists();
    })
</script>
