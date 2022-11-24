<template>


    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Monthly sales report of year &nbsp; <strong style="color: green;"> {{year}}</strong>

            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-spacer></v-spacer>
                    <v-col cols="auto" md="3">
                        <v-select
                            label="Year"
                            :items="years"
                            dense
                            v-model="year"
                            @change="getMonthlySalesReport()"
                        ></v-select>
                    </v-col>
                </v-row>
                <column-chart :data="data2"></column-chart>
            </v-card-text>
        </v-card>
    </v-col>



</template>


<script>

    export default {
        components:{
        },
        mounted() {
            this.getMonthlySalesReport()
            this.getCartYears()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                data:[],
                data2:[],
                year: this.getCurrentYear(),
                years:[]
            }
        },
        methods: {

            getMonthlySalesReport(){
                var _this = this;
                axios.get('/api/getMonthlySalesReport/'+this.year+'?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.month, row.amount])
                    });
                })
                .catch(function (error) {
                })
            },
            getCartYears(){
                var _this = this;
                axios.get('/api/getCartYears?token='+this.token)
                .then(function (response) {
                    _this.years = response.data.data;
                })
                .catch(function (error) {
                })
            },

        },
        computed: {

        }
    }
</script>
