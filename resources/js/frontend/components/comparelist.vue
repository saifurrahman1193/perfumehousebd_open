<template>
    <v-app>
        <subheading_f></subheading_f>

        <v-sheet
            :class="isMDAGT?'mt-12':'mt-6'"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >


            <v-row
                justify="center"
                :class="isSMALT?'':'mt-8'"
                >
                <v-card-title class=" font-weight-bold " :class="isXS ?'h6' : 'display-1'">
                    <span  style="color:rgb(74, 74, 74)" v-text="(calculatedComparelist.length==0 && loading==false)? 'No product added to the compare!':'Compare'"></span>
                </v-card-title>
            </v-row>


            <v-row v-if="loading" justify="center" align="center">
                <v-progress-circular
                    indeterminate
                    color="#C9151B"
                    size="70"
                    class="mx-auto mt-6"
                    >
                </v-progress-circular>
            </v-row>


            <v-row :class="isSMALT ? 'px-1' :'px-12' ">
                <v-simple-table  dense v-if="calculatedComparelist.length>0" style="width: 100% !important;">
                    <tbody>
                        <tr>
                            <th class="text-left">Product Summary</th>
                            <td v-for="(product, index) in calculatedComparelist" :key="index">
                                <v-card
                                    color="grey lighten-4"
                                    max-width="259"
                                    class="mx-1"
                                    >
                                    <productcarddetails_f :product="product" ></productcarddetails_f>

                                </v-card>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-left">Brand</th>
                            <td v-for="(product, index) in calculatedComparelist" :key="index">
                                {{product.brand}}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-left">Review Rating</th>
                            <td v-for="(product, index) in calculatedComparelist" :key="index">
                                <v-rating
                                        v-model="product.rating"
                                        background-color="orange lighten-3"
                                        color="#C9151B"
                                        medium
                                        dense
                                        readonly
                                        half-increments
                                    >
                                </v-rating>
                            </td>
                        </tr>

                    </tbody>
                </v-simple-table>
            </v-row>

            <v-spacer></v-spacer>


                <!-- <th class="text-left">Product Summary</th>
                <th class="text-left">Price</th>
                <th class="text-left">Brand</th>
                <th class="text-left">Review Rating</th> -->

        </v-sheet>

        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <footer_f></footer_f>

    </v-app>
</template>


<script>
    import { mapState, mapGetters } from 'vuex'

    import subheading_f from './subheading.vue'
    import productcarddetails_f from './productcarddetails.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'

    import footer_f from './footer.vue'
    import responsive from '../../mixins/responsive.vue'


    export default {
        mounted () {
            this.topFunction()
            this.getProducts();
        },
        components:{
           subheading_f, productcarddetails_f, latestviewsproducts_f, footer_f,
        },
        mixins: [responsive],
        data () {
            return {
                token: localStorage.getItem('token'),
                products:[],
                loading: true,
            }
        },
        methods: {
            getProducts(){
                var _this = this;
                axios.get('/api/getProducts')
                .then(function (response) {
                    _this.products = response.data.data;
                    _this.loading = false;
                    _this.getDiscountCalculation()
                })
                .catch(function (error) {
                    _this.loading = false;
                })
            },

            getDiscountCalculation(){
                var _this = this;
                axios.post('/api/getDiscountCalculation', {token: this.token, products: this.products})
                .then(function (response) {
                    _this.products=response.data.data
                })
                .catch(function (error) {
                })
            },

        },
        computed: {
            ...mapState([
                 'compareList'
            ]),
             calculatedComparelist:function(){

                var statement = false;

                return this.products.filter(product => {
                    statement =false
                     this.compareList.forEach(comparelist => {

                         if (product.productId==comparelist) {
                             statement=true
                         }
                     });
                    return statement
                 });

             }
        },
        metaInfo: {
            titleTemplate: '%s | Compare'
        }

    }
</script>

<style scoped>
    .tree-node:hover{
        text-decoration : underline !important;
    }
</style>

