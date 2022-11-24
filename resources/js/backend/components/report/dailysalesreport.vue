<template>


    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Daily sales report of year &nbsp; <strong style="color: green;">{{year}}</strong>&nbsp; & month &nbsp; <strong style="color: green;">{{monthNameLong}}</strong>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-spacer class="hidden-sm-and-down"></v-spacer>
                    <v-col cols="auto" md="3" sm="6" xs="12">
                        <v-select
                            label="Year"
                            :items="years"
                            dense
                            v-model="year"
                            @change="getCartMonths(); data2=[]; monthNameLong=''; monthNumber=''"
                        ></v-select>
                    </v-col>
                    <v-col cols="auto" md="3" sm="6" xs="12">
                        <v-autocomplete
                            label="Month"
                            :items="months"
                            item-text="cartMonthName"
                            item-value="cartMonthNumber"
                            dense
                            id="cartMonthNumber"
                            name="cartMonthNumber"
                            v-model="monthNumber"
                            @change="setMonth(); getDailySalesReport()"
                        ></v-autocomplete>
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
            this.getDailySalesReport()
            this.getCartYears()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                data:[],
                data2:[],
                year: this.getCurrentYear(),
                years:[],
                months:[],
                monthNumber: this.getCurrentMonthNumber(),
                monthNameLong: this.getCurrentMonthNameLong(),
            }
        },
        methods: {

            getDailySalesReport(){
                var _this = this;
                axios.get('/api/getDailySalesReport/'+this.year+'/'+this.monthNumber+'?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.day, row.amount])
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
            getCartMonths(){
                var _this = this;
                axios.get('/api/getCartMonths/'+this.year+'?token='+this.token)
                .then(function (response) {
                    _this.months = response.data.data;
                })
                .catch(function (error) {
                })
            },
            setMonth(){
                var _this= this
                this.monthNameLong = this.months.filter(function(month){
                    return (month.cartMonthNumber==_this.monthNumber)
                }).map(function (row) {
                    return row.cartMonthName;
                })[0];
            }

        },
        computed: {

        }
    }
</script>
