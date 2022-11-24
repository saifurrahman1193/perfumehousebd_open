<template>
    <v-app>
      <subheading_f></subheading_f>

      <v-sheet
          class="mx-auto   " :class="isSMALT?'pa-2 mt-2':'mt-12 ma-12 pa-12'"
          elevation="0"
          width="100%"
          color="white"
      >
        <v-toolbar-title class=" mt-2" :class="isSMALT?'title':'display-1'"> Checkout</v-toolbar-title>
        <v-divider></v-divider>



        <div v-if="!(userInfo.phone)">
            <v-toolbar-title class="headline"  :class="isSMALT?'title':'display-1'"><v-icon>phone</v-icon> Please provide your phone number</v-toolbar-title>

            <v-col cols="12" xs="12" sm="12" md="6">
                <v-row  class="pa-5">
                    <v-textarea name="phone" label="Phone number"  v-model="phone" auto-grow  rows="2" counter clearable
                    placeholder="E.g. 01703xxxxxx"
                    prepend-inner-icon="label_important"
                    append-outer-icon="send"
                    @click:append-outer="profileUpdate"
                    >
                    </v-textarea>
                </v-row>
            </v-col>
        </div>



        <div v-if="(userInfo.phone)">
            <div v-if="cartProducts.length && userInfo.phone">
                <v-toolbar-title class="headline"  :class="isSMALT?'title':'display-1'"><v-icon>shopping_basket</v-icon> Bag Items</v-toolbar-title>
                <v-divider></v-divider>
                <v-simple-table>
                    <template v-slot:default  >
                        <thead >
                            <tr>
                                <th class="text-left title" :class="isXS?'subtitle-2':''" >Product</th>
                                <th class="text-left title" :class="isXS?'subtitle-2':''">Price</th>
                                <th class="text-left title" :class="isXS?'subtitle-2':''">Discount</th>
                                <th class="text-left title" :class="isXS?'subtitle-2':''" style="padding-left: 35px; width:200px !important; text-align:center !important;" >Qty</th>
                                <th class="text-left title" :class="isXS?'subtitle-2':''">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in cartProducts" :key="product.productId">
                                <td>
                                    <v-list color="transparent">
                                        <v-list-item >
                                            <v-list-item-icon>
                                                <v-img
                                                    :aspect-ratio="16/9"
                                                    :src="product.productPicturePath || '/uploads/no_image.png'"
                                                    contain
                                                    :width="isSMALT? 50: 80"
                                                    @click="imageZoom(product.productPicturePath)"
                                                    style="cursor: zoom-in;"
                                                >
                                                    <template v-slot:placeholder>
                                                        <v-row
                                                        class="fill-height ma-0"
                                                        align="center"
                                                        justify="center"
                                                        >
                                                            <v-progress-circular indeterminate color="#C9151B"></v-progress-circular>
                                                        </v-row>
                                                    </template>

                                                </v-img>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title v-text="product.productName"></v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </td>
                                <td>৳ {{ product.sellingPrice || 0 }}</td>
                                <td>৳ {{ product.subTotalTotalDiscountAmount || 0 }}</td>
                                <td  style=" text-align:center;">
                                    <v-btn  text  small @click="subtractProductQtyFromCart(product.productId)"><v-icon>remove</v-icon></v-btn>
                                    {{ product.sellingQty }}
                                    <v-btn text  small @click="addQtyToGuestCart(product.productId)"><v-icon>add</v-icon></v-btn>
                                </td>
                                <td>৳ {{ product.subTotalWithDiscount || 0}}</td>
                            </tr>
                            <tr></tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <!-- <td>
                                    <v-icon>card_giftcard</v-icon>
                                    Apply Voucher
                                </td> -->
                                <td colspan="4">
                                    <div style="float: right; width:300px;">
                                        <v-expansion-panels focusable class="mt-1">
                                            <v-expansion-panel style="width:300px;">
                                                <v-expansion-panel-header left>
                                                    Apply Voucher
                                                    <template v-slot:actions>
                                                        <v-icon color="#C9151B">
                                                        card_giftcard
                                                        </v-icon>
                                                    </template>
                                                </v-expansion-panel-header>
                                                <v-expansion-panel-content>
                                                    <applybadges />
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
                                    </div>
                                </td>
                                <td  :colspan="isXS?'2':''" >
                                    {{ discountbadgeDiscount }}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right " >Delivery Charge</th>
                                <th :colspan="isXS?'2':''" >৳ {{deliveryCharge}}</th>
                            </tr>

                            <tr>
                                <th colspan="4" class="text-right title"  :class="isXS?'subtitle-1':''">Total</th>
                                <th :colspan="isXS?'2':''"  :class="isXS?'':'title'">৳ {{cartProductsTotalSum || 0}}</th>
                            </tr>

                        </tfoot>
                    </template>
                </v-simple-table>





                <v-spacer></v-spacer>
                <v-toolbar-title class="headline"   :class="isSMALT?'title mt-5':'display-1'" ><v-icon>place</v-icon>
                Delivery Address
                <span class="subtitle-1 red--text" v-if="!deliveryaddress.deliveryAddressId" style="white-space:normal;">(Select a delivery address below)</span>
                </v-toolbar-title>
                <v-divider></v-divider>

                <deliverydetails_f></deliverydetails_f>
            </div>

            <div v-else>
                <v-toolbar-title class="subtitle text-center mx-auto" v-if="isLoaded">
                    <v-progress-circular
                            indeterminate
                            color="#C9151B"
                            size="70"
                            class="mx-auto"
                            >
                        </v-progress-circular>
                </v-toolbar-title>
                <v-toolbar-title class="subtitle text-center" v-if="!isLoaded"> No product added to the cart!</v-toolbar-title>
                <v-skeleton-loader  :type="type" ref="skeleton" class="ml-5 "></v-skeleton-loader>
            </div>



            <v-row class="ma-5"  v-if="cartProducts.length">
                <v-spacer></v-spacer>
                <v-btn
                    color="red lighten-1"
                    class="ma-2 white--text text-capitalize"
                    large
                    @click="confirmOrder()"
                    :loading="progress_mode"
                    :disabled="progress_mode || (cartProductsTotalSum||0)==0"
                    >
                    Confirm Order
                    <v-icon >chevron_right</v-icon>
                </v-btn>

                <!-- <v-btn
                    color="red lighten-1"
                    class="ma-2 white--text text-capitalize"
                    large
                    v-if="progress_mode"
                    >
                    Processing...
                </v-btn> -->
            </v-row>
        </div>




      </v-sheet>

    <v-spacer></v-spacer>
    <!--<latestviewsproducts_f></latestviewsproducts_f>-->
    <!--<latestbestsellingtopratedproducts_f></latestbestsellingtopratedproducts_f>-->
    <footer_f></footer_f>

    <modal_alert
            :s_alert="s_alert"
            s_msg="Order Confirmed!"
            :e_alert="e_alert"
            :e_msg="e_msg||'Something went wrong!'"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"

        ></modal_alert>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>


    </v-app>
</template>

<script>

import { mapState } from 'vuex'

import addtocartactionsMethodMixin from '../../mixins/addtocartactions.vue'

import subheading_f from './subheading.vue'
import deliverydetails_f from './deliverydetails.vue'


import modal_alert from './modals/alert.vue'
import modal_alert_mixin from './modals/alertMixins.vue'
import latestviewsproducts_f from './latestviewsproducts.vue'
// import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
import footer_f from './footer.vue'
import applybadges from './applybadges.vue'

// zoom
import zoom_modal from './zoom/zoom.vue'
import zoom_mixin from './zoom/zoomMixins.vue'
import responsive from '../../mixins/responsive.vue'



  export default {
    components:{
        subheading_f, deliverydetails_f, modal_alert, latestviewsproducts_f, footer_f, zoom_modal, applybadges
        // latestbestsellingtopratedproducts_f
    },
    mixins: [addtocartactionsMethodMixin, modal_alert_mixin, zoom_mixin, responsive],
    data() {
        return {
                token:localStorage.getItem('token'),
                products:[],
                cartProductsTotalSum:0,
                totalDiscountAmount: 0,
                deliverySettingPrice:0,
                outsideDahakeDeliveryPrice:0,
                cartProductsSubTotalSum: 0,
                totalQty: 0,
                types:[],
                type: 'table',
                progress_mode: false,
                cartId: null,
                e_msg:null,
                isLoaded: false,

                discountbadges: [],
                // discountbadgeDiscount: 0,
                discountbadge: {},
                phone:'',

        }
    },
    mounted() {
        this.getDiscountBadges()
        this.topFunction()
        this.getProducts();
        this.getDeliverySetting();
        if (this.$refs.skeleton) {
            this.types = Object.keys(this.$refs.skeleton.rootTypes)
        }
    },

    methods: {

        getProducts(){

            var listProducts = this.cart
            var listProductsProductIds=[]

            listProducts.forEach(element => {
                listProductsProductIds.push(parseInt(element.productId))
            });


            this.isLoaded=true
            var _this = this;
            axios.post('/api/getProductsWithProductIds', { 'products': listProductsProductIds})
            .then(function (response) {
                _this.products = _this.objectToArray(response.data.data);
                _this.type = ""
                _this.getDiscountCalculation()
                _this.isLoaded=false

            })
            .catch(function (error) {
                _this.isLoaded=false
            })
        },
        getDeliverySetting(){
            var _this = this;
            axios.get('/api/getDeliverySetting')
            .then(function (response) {
                _this.deliverySettingPrice = response.data.data.deliverySettingPrice;
                _this.outsideDahakeDeliveryPrice = response.data.data.outsideDahakeDeliveryPrice;
            })
            .catch(function (error) {
            })
        },
        confirmOrder(){
            console.log('confirm order')
            this.progress_mode=true

            var _this = this;
            axios.post('/api/confirmOrder?token='+this.token, {
                cartProducts: this.cartProducts,
                deliveryCharge: parseInt(this.deliveryCharge),
                cartProductsSubTotalSum: parseInt(this.cartProductsSubTotalSum),
                cartProductsTotalSum: parseInt(this.cartProductsTotalSum),
                deliveryaddress: this.deliveryaddress,
                totalQty: parseInt(this.totalQty),
                totalDiscountAmount: parseInt(this.totalDiscountAmount),
                discountBadgeId: this.userInfo.discountBadgeId,
                discountbadgeDiscount: this.discountbadgeDiscount

            })
            .then(function (response) {
                // _this.s_alert = true;
                localStorage.setItem("guestCart", []);
                _this.$store.commit('CLEAR_CART')
                _this.guestCart=[]
                _this.progress_mode=false
                _this.cartId = response.data.cartId
                _this.gotorouterview('/payment/'+_this.cartId)
            })
            .catch(function (error) {
                if (!_this.deliveryaddress.areaId) {
                    _this.e_msg="Please select a delivery address."
                }
                _this.e_alert = true;
                _this.progress_mode=false

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
        getDiscountBadges(){
            var _this = this;
            axios.get('/api/getDiscountBadges?token='+this.token)
            .then(function (response) {
                _this.discountbadges=response.data.data
            })
            .catch(function (error) {
            })
        },
        profileUpdate(){
            this.orderInstructionAddProgress_mode=true
            var _this = this;
            axios.post('/api/profileUpdate?token='+this.token, {"phone": this.phone})
            .then(function (response) {
                _this.$store.dispatch('checkIsLoggedIn')
                alert("Profile updated!")
            })
            .catch(function (error) {
            })
        },
    },

    computed: {
        ...mapState([
            'deliveryaddress', 'cart', 'userInfo'
        ]),
        cartProducts: function () {

            var _this = this;

            var productArray= [];

            this.cartProductsSubTotalSum = 0;
            this.totalQty=0

            this.totalDiscountAmount=0;


            this.products.forEach(product => {
                _this.guestCart.forEach(guestCartProduct => {
                    if (product.productId == guestCartProduct.productId) {

                        productArray.push(product.productId)

                        product.sellingQty=guestCartProduct.qty
                        product.subTotal=product.sellingPrice * guestCartProduct.qty



                        product.subTotalDiscountAmount=product.discountAmount

                        product.subTotalTotalDiscountAmount=product.discountAmount*guestCartProduct.qty
                        if(product.discountPercent>0) product.subTotalTotalDiscountAmount=(product.sellingPrice*guestCartProduct.qty*(product.discountPercent/100))
                        else if(product.discountAmount>0) product.subTotalTotalDiscountAmount=guestCartProduct.qty*product.discountAmount



                        product.subTotalWithDiscount = product.subTotal-product.subTotalTotalDiscountAmount

                        _this.cartProductsSubTotalSum += product.subTotal;
                        _this.totalDiscountAmount += product.subTotalTotalDiscountAmount

                        _this.totalQty+=parseFloat(guestCartProduct.qty)

                    }
                });
            });



            this.cartProductsTotalSum = this.cartProductsSubTotalSum + parseFloat(this.deliveryCharge)-parseFloat(this.totalDiscountAmount)-this.discountbadgeDiscount;

            return this.products.filter(function(product){
                return productArray.includes(product.productId)
            })
        },
        deliveryCharge(){
            var charge = 0;
            if (this.deliveryaddress.areaId && this.deliveryaddress.areaId>1) {
                charge = this.outsideDahakeDeliveryPrice
            }
            else if (this.deliveryaddress.areaId && this.deliveryaddress.areaId==1) {
                charge = this.deliverySettingPrice
            }
            else{
                charge = 0
            }
            return charge;
        },
        discountbadgeDiscount(){
            var discount = 0;
            if (this.userInfo.discountBadgeId) {
                this.discountbadge = this.discountbadges.filter((item)=>{
                    return item.discountBadgeId==this.userInfo.discountBadgeId
                });

                this.discountbadge = this.arrayToObject(this.discountbadge)

                discount = ( this.cartProductsSubTotalSum *  this.discountbadge.discBadgeDiscountPercent) / 100
                if (discount>this.discountbadge.discountUpToAmount)
                {
                    discount=this.discountbadge.discountUpToAmount
                }
            }
            else{
                discount = 0
            }
            return discount;
        }
    },
    metaInfo: {
        titleTemplate: '%s | Checkout'
    },
    watch: {
        'userInfo.phone': {
            handler(val){
                this.phone = this.userInfo.phone
                if (this.$refs.skeleton) {
                    this.types = Object.keys(this.$refs.skeleton.rootTypes)
                }
            },
            deep: true
        }
    }
  }
</script>
