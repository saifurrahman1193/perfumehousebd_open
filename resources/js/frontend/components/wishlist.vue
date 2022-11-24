<template>
    <v-app>
        <subheading_f></subheading_f>

        <v-sheet
            :class="isMDAGT?'mt-12':'mt-6'"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
             :style="isSMALT?'padding-left:14px':'padding-left:20px'"
         >

            <!-- <v-row class="text-center" :class="isSMALT?'':'mt-8'" >
                <v-col class="text-center">
                    <v-toolbar-title class="subtitle text-center" v-if="calculatedWishlist.length==0 && loading==false"> </v-toolbar-title>
                    <v-toolbar-title class="subtitle text-center" v-else>Wishlist</v-toolbar-title>
                </v-col>
            </v-row> -->

            <v-row
                justify="center"
                :class="isSMALT?'':'mt-8'"
                >
                <v-card-title class=" font-weight-bold " :class="isXS ?'h6' : 'display-1'">
                    <span  style="color:rgb(74, 74, 74)" v-text="(calculatedWishlist.length==0 && loading==false)? 'No product added to the wishlist!':'Wishlist'"></span>
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


            <v-row>
                <v-col
                    v-for="product in calculatedWishlist || [] "
                    :key="product.productId"
                    cols="auto"
                    :width="isSMALT? 155: 250"
                    class="mx-0 px-0"
                    :style="isSMALT?'margin-right:4px !important;':'margin-right:10px !important;'"
                >
                    <v-card
                        color="grey lighten-4"
                        :width="isSMALT? 155: 250"
                        >
                        <productcarddetails_f :product="product"></productcarddetails_f>

                    </v-card>
                </v-col>
            </v-row>


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
           subheading_f, productcarddetails_f, latestviewsproducts_f, footer_f
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
                'wishList'
            ]),
             calculatedWishlist:function(){

                var statement = false;

                return this.products.filter(product => {
                    statement =false
                     this.wishList.forEach(wishlist => {

                         if (product.productId==wishlist) {
                             statement=true
                         }
                     });
                    return statement
                 });


             }
        },
        metaInfo: {
            titleTemplate: '%s | Wishlist'
        }

    }
</script>

<style scoped>
    .tree-node:hover{
        text-decoration : underline !important;
    }
</style>

