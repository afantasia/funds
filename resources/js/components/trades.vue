<template>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><h1>{{ title }}</h1></div>
                <div><button class="btn btn-primary" type="button">매수&매도</button> </div>
            </div>
        </div>
        <div class="card-body">
            <easy-data-table
                :headers="headers"
                :items="items"

                :filter-options="filterOptions"
            >
                <template #header-name="header">
                    <div class="filter-column">
<!--                        <img src="../images/eglass-filter.png" class="filter-icon" @click.stop="showNameFilter=!showNameFilter">-->
                        {{ header.text }}
                        <div class="filter-menu" v-if="showNameFilter">
                            <input v-model="nameCriteria"/>
                        </div>
                    </div>
                </template>
                <template #header-age="header">
                    <div class="filter-column">
<!--                        <img src="../images/eglass-filter.png" class="filter-icon" @click.stop="showAgeFilter=!showAgeFilter">-->
                        {{ header.text }}
                        <div class="filter-menu filter-age-menu" v-if="showAgeFilter">
                            <Slider v-model="ageCriteria" class="slider"/>
                        </div>
                    </div>
                </template>
                <template #header-favouriteSport="header">
                    <div class="filter-column">
<!--                        <img src="../images/eglass-filter.png" class="filter-icon" @click.stop="showFavouriteSportFilter=!showFavouriteSportFilter">-->
                        {{ header.text }}
                        <div class="filter-menu filter-sport-menu" v-if="showFavouriteSportFilter">
                            <select
                                class="favouriteSport-selector"
                                v-model="favouriteSportCriteria"
                                name="favouriteSport"
                            >
                                <option value="swimming">
                                    swimming
                                </option>
                                <option value="basketball">
                                    basketball
                                </option>
                                <option value="running">
                                    running
                                </option>
                                <option value="football">
                                    football
                                </option>
                                <option value="all">
                                    all
                                </option>
                            </select>
                        </div>
                    </div>
                </template>
            </easy-data-table>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import { Tooltip } from "bootstrap/dist/js/bootstrap.esm.min.js";


export default {
    data() {
        const ar=[];
        for(var i=0;i<1;i++){
            ar.push( { player: "Stephen Curry", team: "GSW", number: i+30, position: 'G', indicator: {"height": '6-'+i+1, "weight": 185}, lastAttended: "Davidson", country: "USA"});
        }
        return {
            lists: [],
            headers: [
                { text: "뽀큐", value: "player" },
                { text: "법규", value: "team"},
                { text: "병헌", value: "number"},
                { text: "뭐", value: "position"},
                { text: "킹쩌라고", value: "indicator.height"},
                { text: "조졌다", value: "indicator.weight", sortable: true},
                { text: "넌뭥데", value: "lastAttended", width: 200},
                { text: "깐츄리", value: "country"},
            ],
            items: ar,
        }
    },
    setup: () => {
        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
        });
        console.log('a');
        return {title:"내거래영역"};
    },
    methods:{
        initFunction:()=>{

            console.log('init functions');

        }
    },
    mounted() {
        this.initFunction();
    }
}
</script>

<style scoped>
.display{width:100vw;}
</style>