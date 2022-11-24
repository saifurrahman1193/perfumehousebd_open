<template>
    <v-app>
        <subheading_f></subheading_f>

        <v-sheet
            class="mt-12"
            elevation="0"
            max-width="100%"
            color="grey lighten-5"
         >

            <v-row class="mt-12 mx-2">



                <v-col  xs="12" sm="12"   md="4"  >
                    <h2 class="font-weight-light">My Orders</h2>

                    <v-skeleton-loader :type="type" ref="skeleton" :class="type?'mb-5':''"></v-skeleton-loader>
                    <v-skeleton-loader :type="type" ref="skeleton" :class="type?'mb-5':''"></v-skeleton-loader>
                    <v-skeleton-loader :type="type" ref="skeleton" :class="type?'mb-5':''"></v-skeleton-loader>
                    <v-skeleton-loader :type="type" ref="skeleton"></v-skeleton-loader>


                    <v-card elevation="2"
                        v-for="cartitem in carts || [] "
                        :key="cartitem.cartId"
                        class="mb-3 pa-2"
                    >


                        <v-row>
                            <v-col>
                                <v-list-item-subtitle class="ml-5">
                                    Order : <span class="font-weight-bold">#{{cartitem.cartId}}</span>
                                </v-list-item-subtitle>

                                <v-list-item-subtitle class="ml-5">
                                    ৳ {{cartitem.cartProductsTotalSum}}
                                </v-list-item-subtitle>

                                <v-list-item-subtitle class="mb-2">
                                    <cartstatus  :cart="cartitem" @reloadData="reloadData()" ></cartstatus>
                                </v-list-item-subtitle>
                            </v-col>
                            <v-col>
                                <div style="float:right;">
                                    <v-list-item-subtitle class="mb-2">
                                        <v-rating
                                            v-model="cartitem.customerRating"
                                            background-color="orange lighten-3"
                                            color="#C9151B"
                                            medium
                                            dense
                                            @input="cusotomerGivingCartRating(cartitem.cartId, cartitem.customerRating)"
                                        >
                                        </v-rating>
                                    </v-list-item-subtitle>
                                    <v-list-item-subtitle v-if="cartitem.isPaymentComplete!=1">
                                        <v-chip @click="gotorouterview('/payment/'+cartitem.cartId)" outlined color="success" label>Make Payment</v-chip>
                                    </v-list-item-subtitle>
                                </div>

                            </v-col>
                        </v-row>

                        <v-card-actions  class="pa-0 ma-0">
                            <v-list-item @click="showCartDetails(cartitem.cartId); " class="pa-0 ma-0 "  v-if="cartitem.cartId!=cart.cartId ">
                                <v-spacer></v-spacer>
                                Show Details
                                <v-icon>keyboard_arrow_down</v-icon>
                                <v-spacer></v-spacer>
                            </v-list-item>

                            <v-list-item @click="hideCartDetails(cartitem.cartId)" class="pa-0 ma-0 " v-if="cartitem.cartId==cart.cartId" style="color:orange !important; background-color:#ffa50017 !important;">
                                <v-spacer></v-spacer>
                                Hide Details
                                <v-icon  style="color:orange !important;">keyboard_arrow_up</v-icon>
                                <v-spacer></v-spacer>
                            </v-list-item>

                        </v-card-actions>
                        <v-card-actions  class="pa-0 ma-0" v-if="isSMALT && cartitem.cartId==cart.cartId"  >
                            <orderdetails_f :cart="cart" :cartdetails="selectedcartdetails"></orderdetails_f>
                        </v-card-actions>
                    </v-card>


                </v-col>

                <v-col   md="8" v-if="isMDAGT" >
                    <div style="position:fixed !important; width:63%;">
                        <h2 class="font-weight-light  text-center" v-if="cart.cartId">Order Details</h2>

                        <orderdetails_f :cart="cart" :cartdetails="selectedcartdetails"></orderdetails_f>
                    </div>

                </v-col>



            </v-row>

        </v-sheet>

        <v-spacer></v-spacer>
        <!-- <latestviewsproducts_f></latestviewsproducts_f> -->
        <footer_f></footer_f>



    </v-app>
</template>


<script>

    import subheading_f from './subheading.vue'
    import orderdetails_f from './orderdetails.vue'
    import cartstatus from './cartstatus.vue'
    import footer_f from './footer.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import responsive from '../../mixins/responsive.vue'


    export default {
        inject: ['theme'],
        mounted () {
            this.topFunction()
            this.getCurrentUserCarts()
            this.getCurrentUserCartDetails()
            this.types = Object.keys(this.$refs.skeleton.rootTypes)
        },
        components:{
           subheading_f,  orderdetails_f, cartstatus, footer_f, latestviewsproducts_f
        },
        data () {
            return {
                token:localStorage.getItem('token'),
                carts:[],
                cartdetails:[],
                selectedcartdetails:[],
                cart:{},
                products:[],
                types:[],
                type: 'list-item-avatar-three-line',

            }
        },
        mixins: [responsive],
        methods: {
            getCurrentUserCarts(){
                var _this = this;
                axios.get('/api/getCurrentUserCarts?token='+this.token)
                .then(function (response) {
                    _this.carts = (response.data.data).reverse();
                    _this.type=""
                })
                .catch(function (error) {
                })
            },
            getCart(cartId){
                this.cart = this.carts.filter(function(c){
                    return c.cartId == cartId
                })[0]
            },
            showCartDetails(cartId){
                // scroll(0,0)
                this.getCart(cartId)
                this.setSelectedCartDetails(cartId)
                console.log('cartId = '+cartId)
            },
            hideCartDetails(){
                this.cart={}
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
            getCurrentUserCartDetails(){
                var _this = this;
                axios.get('/api/getCurrentUserCartDetails?token='+this.token)
                .then(function (response) {
                    _this.cartdetails = response.data.data;
                })
                .catch(function (error) {
                })
            },
            setSelectedCartDetails(cartId){
                this.selectedcartdetails = this.cartdetails.filter(function(cartdetail){
                    return cartdetail.cartId == cartId
                })
            },
            cusotomerGivingCartRating(cartId, rating){
                var _this = this;
                axios.post('/api/cusotomerGivingCartRating?token='+this.token, {
                    cartId : cartId,
                    rating : rating,

                })
                .then(function (response) {

                })
                .catch(function (error) {
                })
            },
            reloadData(){
                this.getCurrentUserCarts();
            }

        },
        metaInfo: {
            titleTemplate: '%s | Order History'
        }
    }
</script>



