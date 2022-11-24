<template>


    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Monthly product visits report of year &nbsp; <strong style="color: green;"> {{year}}</strong>

            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-spacer></v-spacer>
                    <v-col  md="6" sm="12" xs="12">

                        <v-autocomplete
                            label="Products"
                            :items="products2"
                            item-text="productName"
                            item-value="productId"
                            dense
                            v-model="productId"
                            @change="getPorductVisitsYears('/'+productId); data2=[]; years=[]; year='' "
                            clearable
                        ></v-autocomplete>
                    </v-col>

                    <v-col  md="6" sm="12" xs="12">
                        <v-select
                            label="Year"
                            :items="years"
                            dense
                            v-model="year"
                            @change="getMonthlyProductVisitsReport(productId>0?'/'+productId:'')"
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
            this.getProducts()
            this.getPorductVisitsYears()
            this.getMonthlyProductVisitsReport()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                data:[],
                data2:[],
                year: this.getCurrentYear(),
                years:[],
                productId:'',
                products:[]
            }
        },
        methods: {

            getMonthlyProductVisitsReport(productIdwithparam=''){
                var _this = this;
                axios.get('/api/getMonthlyProductVisitsReport/'+this.year+productIdwithparam+'?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.month, row.visits])
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
