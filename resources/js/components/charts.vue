<template>
    <div class="card">
        <div class="card-header">
            <h1>{{ title }}</h1>
            ㅇㅇㅇㅇ
            <select  @change="getStockHistory();">
                <option
                    v-for="item in companys"
                    :value="item.id"
                 >{{item.name}}</option>
            </select>
        </div>
        <div class="card-body">
            <div id="chart">
                <apexchart type="candlestick" height="350" id="chart"></apexchart>
            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios';
import ApexCharts from 'apexcharts';
export default {
    data() {
        return {
            companys: [],
            chartOptions: {},
            series: {},
        }
    },
    setup: () => {
        return {title:"회사 주가 목록"};
    },
    methods:{
        getCompany(){
            const $companys=this.companys;
            $companys.push({id:"","name":"선택하세요"});
            axios.get("/stock/getCompany").then((result)=>{
                result.data.forEach(function(data,index){
                    $companys.push({"id":data.id,"name":data.name});
                });
            });

        },
        getStockHistory(){
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
                        text: 'CandleStick Chart',
                        align: 'left'
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: {
                        tooltip: {
                            enabled: true
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            });
        }
    },
    mounted() {
        this.getCompany();
    }
}
</script>