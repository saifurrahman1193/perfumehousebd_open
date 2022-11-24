<template>

    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Yearly product visits report

            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-spacer class="hidden-sm-and-down"></v-spacer>
                    <v-col  md="6" sm="12" xs="12">
                        <v-autocomplete
                            label="Products"
                            :items="products2"
                            item-text="productName"
                            item-value="productId"
                            dense
                            v-model="productId"
                            @change="getYearlyProductVisitsReport('/'+productId); "
                            clearable
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
            this.getProducts()
            this.getYearlyProductVisitsReport()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                data:[],
                data2:[],
                products:[],
                productId:''
            }
        },
        methods: {

            getYearlyProductVisitsReport(productIdparam=''){
                var _this = this;
                axios.get('/api/getYearlyProductVisitsReport'+productIdparam+'?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.year, row.visits])
                    });
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
