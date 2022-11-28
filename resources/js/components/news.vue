<template>
    <div class="card">
        <div class="card-header"><h1>{{ title }}</h1></div>
        <div class="card-body">

            <table class="tab-content" width="100%">
                <tr>
                    <th>타이틀</th>
                    <th>날짜 </th>
                </tr>
                <tr v-for="item in lists">
                    <td>
                        <a href="#" data-bs-toggle="tooltip"
                            data-bs-html="true"
                           :title="item.title+'되었습니다.<br> ㅈ문가들은 '+item.name+'사 주식의 '+ (item.type == 'PLUS' ? '긍정적' : '부정적')+'인 영향을 끼칠거라 보도햇습니다.'"
                        >{{item.title}}</a>
                    </td>
                    <td>
                        {{item.created_at}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { Tooltip } from "bootstrap/dist/js/bootstrap.esm.min.js";
export default {
    data() {
        return {
            lists: []
        }
    },
    setup: () => {
        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
        });
        return {title:"뉴스영역"};
    },
    methods:{
        loadFeed(){
            const $lists=this.lists;
            axios.get("/stock/getNews").then((result)=>{
                result.data.forEach(function(data,index){
                    $lists.push(data);
                });
            });
        },
        getCompany(event){
            const $companys=this.companys;
            axios.get("/stock/getCompany").then((result)=>{
                result.data.forEach(function(data,index){
                    $companys.push({"id":data.id,"name":data.name});
                });
            });

        },
        getStockHistory(event){
            console.log('getStockHistory');
            axios.get("/stock/getStockHistory/"+event.target.value).then((result)=> {
                console.log(result.data);

            });
        }
    },
    mounted() {
        this.loadFeed();
    }
}
</script>