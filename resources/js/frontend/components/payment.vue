<template>
    <v-app>
      <subheading_f></subheading_f>

      <v-sheet
          class="mx-auto mt-12 pa-12 ma-12"
          elevation="0"
          width="100%"
          color="white"
      >

        <div class="pa-6 mb-5" style="background-color:#ffa5001f;" v-if="isLoaded">
            <v-toolbar-title class="subtitle text-center" v-if="cart.cartId">
                <v-icon>shopping_cart</v-icon>
                Order Number {{cart.cartId}}
                <br>
                <br>
                <h6>We have received your order. Please spare us some time to process your order. Our customer service will contact with you shortly.</h6>
            </v-toolbar-title>

            <v-toolbar-title class="subtitle text-center" v-else>Order Not Found </v-toolbar-title>
        </div>

        <v-skeleton-loader :type="type" ref="skeleton" class="ml-5 " v-else></v-skeleton-loader>



        <div  class="pa-5" style="border:1px solid #8080804a;" v-if="cart.cartId">
            <!-- <v-toolbar-title class="subtitle text-center mb-6" >Do you want to <span style="color:#0080008a; font-weight:bold;">Pay Now</span>? </v-toolbar-title> -->

            <!-- <bkashtransactionmethod :cart="cart"  v-if="cart.cartId" @getCurrentUserCart="getCurrentUserCart()"/> -->

            <v-toolbar-title class="subtitle  mb-5" style="color:grey;">
                <v-icon>payments</v-icon>
                Payment method : <span class="font-weight-bold">Cash on delivery</span>
            </v-toolbar-title>

        </div>


        <v-row v-if="cart.cartId">


            <v-col cols="auto" xs="12"  sm="12" md="6" style="width:100%;">
                <v-card class="pa-5" >
                    <v-toolbar-title class="subtitle text-center mb-5" style="color:grey;">
                        <v-icon>approval</v-icon>
                        Cart Status
                    </v-toolbar-title>
                    <cartstatus  :cart="cart"  ></cartstatus>

                </v-card>
            </v-col>


            <v-col cols="auto" xs="12"  sm="12" md="6" style="width:100%;">
                <v-card class="pa-5" >
                        <v-toolbar-title class="subtitle text-center" style="color:grey;">
                            <v-icon>receipt</v-icon>
                            Order Summary
                        </v-toolbar-title>
                    <v-list-item-subtitle >
                        <v-simple-table   >
                            <template v-slot:default >
                            <tbody>
                                <tr>
                                <td>Subtotal</td>
                                <td>৳ {{cart.cartProductsSubTotalSum}}</td>
                                </tr>
                                <tr>
                                <td>Delivery Charge</td>
                                <td>৳ {{cart.deliveryCharge}}</td>
                                </tr>
                                <tr>
                                <td>Order Total</td>
                                <td>৳ {{cart.cartProductsTotalSum}}</td>
                                </tr>
                                <tr style="color:#ee0000c7;">
                                <td >Amount Paid</td>
                                <td>৳ {{cart.transactionAmountTotal}}</td>
                                </tr>
                                <tr style="font-weight:bold;">
                                <td>Due</td>
                                <td>৳ {{cart.cartProductsTotalSum-cart.transactionAmountTotal}}</td>
                                </tr>
                            </tbody>
                            </template>
                        </v-simple-table>
                    </v-list-item-subtitle>
                </v-card>
            </v-col>
            <v-col cols="auto" xs="12"  sm="12" md="6" style="width:100%;">
                <div class="pa-5" style="border:2px dashed #8080804a;">
                    <v-toolbar-title class="subtitle text-center mb-5" style="color:grey;">
                        <v-icon>local_shipping</v-icon>
                        Delivery Address
                    </v-toolbar-title>
                    <v-toolbar-title class="subtitle text-center" style="color:#000000b8; white-space: normal;" :style="(isXS?'font-size:12px;':'')">{{cart.shippingAddress}} <strong>{{'('+cart.area+')'}}</strong></v-toolbar-title>
                </div>
            </v-col>
            <v-col cols="12" xs="12" sm="12" md="6" v-if="cart.cartId">
                <v-row  class="pa-5">
                    <v-textarea name="customerDeliveryComment" label="Order Instruction"  v-model="cart.customerDeliveryComment" auto-grow  rows="2" counter clearable
                    placeholder="E.g. Call me when you arrive"
                    prepend-inner-icon="label_important"
                    append-outer-icon="send"
                    @click:append-outer="orderInstructionAdd"
                    >
                    </v-textarea>

                    <!-- <v-btn color="success" @click="orderInstructionAdd()" v-if="!orderInstructionAddProgress_mode">Submit<v-icon right dark>send</v-icon></v-btn> -->
                    <!-- <v-btn color="success"  v-if="orderInstructionAddProgress_mode">Processing...</v-btn> -->
                </v-row>

            </v-col>

        </v-row>


      </v-sheet>

    <v-spacer></v-spacer>
    <!-- <latestviewsproducts_f></latestviewsproducts_f>
    <latestbestsellingtopratedproducts_f></latestbestsellingtopratedproducts_f> -->
    <footer_f></footer_f>



    </v-app>
</template>

<script>


import subheading_f from './subheading.vue'
import latestviewsproducts_f from './latestviewsproducts.vue'
import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
import footer_f from './footer.vue'
// import bkashtransactionmethod from './bkashtransactionmethod.vue'
import responsive from '../../mixins/responsive.vue'
import cartstatus from './cartstatus.vue'


  export default {
    components:{
        subheading_f,latestviewsproducts_f, footer_f, latestbestsellingtopratedproducts_f, cartstatus
    },
    mixins: [responsive],
    data() {
        return {
                token:localStorage.getItem('token'),
                cartId: this.$route.params.cartId,
                cart: {},
                types:[],
                type: 'article',
                isLoaded: false,
                // orderInstructionAddProgress_mode: false,

        }
    },
    mounted() {
        this.getCurrentUserCart()
        this.types = Object.keys(this.$refs.skeleton.rootTypes)
    },

    methods: {
        getCurrentUserCart(){
            var _this = this;
            axios.get('/api/getCurrentUserCart/'+this.cartId+'?token='+this.token)
            .then(function (response) {
                _this.cart = response.data.data;
                _this.isLoaded= true;
            })
            .catch(function (error) {
                _this.isLoaded= true;
            })
        },
        orderInstructionAdd(){
            this.orderInstructionAddProgress_mode=true
            var _this = this;
            axios.post('/api/cartUpdate?token='+this.token, {"cartId": this.cartId, "customerDeliveryComment" : this.cart.customerDeliveryComment})
            .then(function (response) {
                _this.getCurrentUserCart()
                // _this.orderInstructionAddProgress_mode=false
                alert("Instruction sent!")
            })
            .catch(function (error) {
                // _this.orderInstructionAddProgress_mode=false
            })
        },

    },

    computed: {


    }
  }
</script>
