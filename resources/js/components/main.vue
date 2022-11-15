<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        찌라시 늬우스!
                    </div>
                    <table class="tab-content">
                    <tr>
                        <th>타이틀</th>
                        <th>날짜 </th>
                    </tr>
                    <tr v-for="item in lists">
                        <td>
                            <div :id="'tooltip-target-'+item.id"><a href="#">{{item.title}}</a></div>
                            <b-tooltip :target="'tooltip-target-'+item.id"  triggers="click" variant="outline-success">
                                {{item.title}} 되었습니다.<br>
                                ㅈ문가들은   {{item.name}}사의 주식에 {{ item.type == 'PLUS' ? "긍정적" : "부정적" }}인<br>
                                영향을 끼칠거라 신들이 보도했습니다.
                            </b-tooltip>
                        </td>
                        <td>
                            {{item.created_at}}
                        </td>
                    </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        오늘의 차트!
                        <select @change="getStockHistory">
                            <option value>=회사선택=</option>
                            <option v-for="company in companys" :value="company.id">{{company.name}}</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <Linechart />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import Linechart from "./linechart.vue";




export default {
    components: {Linechart},
    data() {
        return {
            lists: [],
            companys:[],
        }
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
                //console.log(result.data);
                Linechart.methods.setValues(result.data);

            });
        }
    },
    mounted() {
        this.loadFeed();
        this.getCompany();
    }
}
</script>

<style scoped>
    .container{max-width: 98vw}
</style>