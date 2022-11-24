<template>


    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Daily product visits report of year &nbsp; <strong style="color: green;">{{year}}</strong>&nbsp; & month &nbsp; <strong style="color: green;">{{monthNameLong}}</strong>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col  md="6" sm="12" xs="12">
                        <v-autocomplete
                            label="Products"
                            :items="products2"
                            item-text="productName"
                            item-value="productId"
                            dense
                            v-model="productId"
                            @change=" getPorductVisitsYears('/'+productId); data2=[]; years=[]; year=''; monthNameLong=''; monthNumber=''; "
                            clearable
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="auto" md="3" sm="6" xs="12">
                        <v-select
                            label="Year"
                            :items="years"
                            dense
                            v-model="year"
                            @change="getProductVisitsMonths('/'+productId); data2=[]; monthNameLong=''; monthNumber=''"
                        ></v-select>
                    </v-col>
                    <v-col cols="auto" md="3" sm="6" xs="12">
                        <v-autocomplete
                            label="Month"
                            :items="months"
                            item-text="productvisitMonthName"
                            item-value="productvisitMonthNumber"
                            dense
                            id="productvisitMonthNumber"
                            name="productvisitMonthNumber"
                            v-model="monthNumber"
                            @change="setMonth(); getDailyProductVisitsReport('/'+productId)"
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
            this.getDailyProductVisitsReport()
            this.getPorductVisitsYears()
            this.getProducts()

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
                productId:'',
                products:[]
            }
        },
        methods: {

            getDailyProductVisitsReport(productIdwithparam=''){
                var _this = this;
                axios.get('/api/getDailyProductVisitsReport/'+this.year+'/'+this.monthNumber+productIdwithparam+'?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.day, row.visits])
                    });
                })
                .catch(function (error) {
                })
            },
            getPorductVisitsYears(productIdwithparam=''){
                var _this = this;
                axios.get('/api/getPorductVisitsYears'+productIdwithparam+'?token='+this.token)
                .then(function (response) {
                    _this.years = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getProductVisitsMonths(productIdwithparam=''){
                var _this = this;
                axios.get('/api/getProductVisitsMonths/'+this.year+productIdwithparam+'?token='+this.token)
                .then(function (response) {
                    _this.months = response.data.data;
                })
                .catch(function (error) {
                })
            },
            setMonth(){
                var _this= this
                this.monthNameLong = this.months.filter(function(month){
                    return month.productvisitMonthNumber==_this.monthNumber
                }).map(function (row) {
                    return row.productvisitMonthName;
                })[0];
            },
            getProducts(){
                var _this = this;
                axios.get('/api/getProducts')
                .then(function (response) {
                    _this.products = response.data.data;
                })
                .catch(function (error) {
                })
            },

        },
        computed: {
            products2: function(){
                var tempArr = []
                tempArr.push({productId:'', productName: 'All'})
                this.products.forEach(element => {
                    tempArr.push(element)
                });

                return tempArr
            }
        }
    }
</script>
