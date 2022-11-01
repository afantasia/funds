<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
                            <b-tooltip :target="'tooltip-target-'+item.id"  triggers="click" delay="{ show: 100, hide: 400}">
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
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            lists: []
        }
    },
    methods:{
        loadFeed(){
            const $lists=this.lists;
            const routeLists=this.$cookies.get("Route");
            axios.get(routeLists['stock.getNews'].uri).then((result)=>{
                result.data.forEach(function(data,index){
                    $lists.push(data);
                });
            });

        },
        bb(event){
            const $dataset=event.target.dataset;

        }
    },
    mounted() {
        const $common=Vue.prototype.$common;
        this.loadFeed()
    }
}
</script>

<style scoped>

</style>