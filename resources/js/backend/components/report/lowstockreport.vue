<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >

            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Low Stock Report
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>
                        <v-btn
                            class="mx-0 mr-2"
                            target="_blank"
                            x-large
                            :href="'/api/stockReportFullExcelReportDownload?token='+token"
                        >
                            Excel Export
                        </v-btn>

                        <v-row>

                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-row>

                        <v-data-table
                        :headers="headers"
                        :items="products"
                        :search="search"
                        sort-by="inStockQty"
                        :loading="loading"
                        loading-text="Loading... Please wait"
                        >

                            <template v-slot:[`item.product`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1" >{{item.productName}}</v-list-item-title>
                                    <v-list-item-title class="pl-1" v-if="item.qty>0" >{{item.qty+' '+ item.uom}}</v-list-item-title>
                                </v-list>
                            </template>

                            <template v-slot:[`item.totalPurchasePrice`]="{ item }">
                                    {{item.purchasePrice * item.inStockQty}}
                            </template>
                            <template v-slot:[`item.totalSalePrice`]="{ item }">
                                    {{item.sellingPrice * item.inStockQty}}
                            </template>

                            <template slot="body.append">
                                <tr class="pink--text">
                                    <th class="title">Totals</th>
                                    <th class="title"></th>
                                    <th class="title">{{numberWithCommas(sumTotalInStockQty( 'inStockQty'))}}</th>
                                    <th class="title">{{'৳ '+numberWithCommas(sumTotalPurchasePrice('purchasePrice', 'inStockQty'))}}</th>
                                    <th class="title">{{'৳ '+numberWithCommas(sumTotalSalePrice('sellingPrice', 'inStockQty'))}}</th>
                                </tr>
                            </template>


                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'
    export default {
        components:{
            subheading,
        },
        mounted() {
            this.getProducts()
            this.loading= true
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Id',
                        align: 'start',
                        // sortable: false,
                        value: 'productId',
                    },
                    { text: 'product', value: 'product' },
                    { text: 'In-Stock', value: 'inStockQty' },
                    { text: 'Total Purchase Price', value: 'totalPurchasePrice' },
                    { text: 'Total Sale Price', value: 'totalSalePrice' },
                ],
                products:[],
                loading: false
            }
        },
        methods: {

            getProducts(){
                this.loading= true

                var _this = this;
                axios.get('/api/getProducts')
                .then(function (response) {
                    _this.products = response.data.data;
                    _this.loading= false
                })
                .catch(function (error) {
                })
            },

            sumTotalInStockQty( inStockQty) {
                return this.products.reduce((a, b) => a + (parseFloat(b[inStockQty]) || 0), 0)
            },

            sumTotalPurchasePrice(purchasePrice, inStockQty) {
                return this.products.reduce((a, b) => a + ((parseFloat(b[purchasePrice]) || 0)*(parseFloat(b[inStockQty]) || 0)), 0)
            },
            sumTotalSalePrice(sellingPrice, inStockQty) {
                return this.products.reduce((a, b) => a + ((parseFloat(b[sellingPrice]) || 0)*(parseFloat(b[inStockQty]) || 0)), 0)
            }

        },
        computed: {

        }
    }
</script>

