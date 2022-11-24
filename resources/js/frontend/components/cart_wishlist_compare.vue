<template>
    <v-row>
        <v-spacer></v-spacer>


        <div :class="isSMALT?'mr-3':'mr-5'" text    style="cursor: pointer;">
            <!-- @click="gotorouterview('/checkout')" -->
            <v-badge  color="green" :content="getCartList.length" :value="getCartList.length" >

                <v-menu
                    offset-y
                    transition="slide-x-transition"
                    bottom
                    :close-on-content-click="false"
                    open-on-hover
                    v-model="bagMenu"

                    >
                    <template v-slot:activator="{ on }">
                        <div v-on="on" @click="bagMenu=true">
                            <span v-if="isSMAGT" style="color: grey;">Bag</span>
                            <v-icon   :color="getCartList.length?'#C9151B':'grey'"   :x-small="isXS">add_shopping_cart</v-icon>
                        </div>
                    </template>
                    <v-list width="260">

                        <v-list-item dense>
                            {{getCartList.length || 0}} items in Bag

                            <v-spacer></v-spacer>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                        <v-btn text v-on="on" @click="bagMenu=false;" >
                                            <v-icon    color="pink">cancel</v-icon>
                                        </v-btn>
                                </template>
                                <span>Close</span>
                            </v-tooltip>
                        </v-list-item>

                        <v-list-item-group >
                            <v-list-item
                            v-for="(item, index) in getCartProductsInfo||[]"
                            :key="index"
                            dense
                            class="px-0"
                            >
                                <productcarddetails_snippet :product="item" :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false" isSmall="true"> </productcarddetails_snippet>
                            </v-list-item>
                            <v-list-item>
                                <v-btn  dark  text  block  class="text-capitalize red accent-4"   @click="gotorouterview('/checkout')">
                                    View Bag
                                </v-btn>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-menu>
            </v-badge>
        </div>


        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <div :class="isSMALT?'mr-3':'mr-5'" text   v-on="on"  style="cursor: pointer;" @click="gotorouterview('/comparelist')">
                    <v-badge  color="green" :content="getCompareList.length" :value="getCompareList.length">
                        <v-icon   :color="getCompareList.length?'#C9151B':'grey'" :x-small="isXS" >compare</v-icon>
                    </v-badge>
                </div>
            </template>

            <span>Go to compare list</span>
        </v-tooltip>

        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <div :class="isSMALT?'mr-3':'mr-5'" text   v-on="on"  style="cursor: pointer;" @click="gotorouterview('/wishlist')">
                    <v-badge  color="green" :content="getWishList.length" :value="getWishList.length">
                        <v-icon  :color="getWishList.length?'#C9151B':'grey'"  :x-small="isXS">favorite</v-icon>
                    </v-badge>
                </div>
            </template>

            <span>Go to wish list</span>
        </v-tooltip>


        <!-- <div :class="isSMALT?'mr-8':'mr-5'" text  style="cursor: pointer;">
            <v-badge  color="green" :content="availableBadgeDiscountPoints" :value="availableBadgeDiscountPoints" >
                <v-menu
                    offset-y
                    transition="slide-x-transition"
                    bottom
                    :close-on-content-click="false"
                    open-on-hover
                    v-model="pointMenu"

                    >
                    <template v-slot:activator="{ on }">
                        <div v-on="on" @click="pointMenu=true">
                            <v-icon   :color="availableBadgeDiscountPoints?'#C9151B':'grey'"   :x-small="isXS">monetization_on</v-icon>
                        </div>
                    </template>

                    <v-list width="260" >

                        <v-list-item dense class="my-0 py-0">
                            <span style="color:green;">
                                {{availableBadgeDiscountPoints || 0}} points available!
                            </span>

                            <v-spacer></v-spacer>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                        <v-btn text v-on="on" @click="pointMenu=false;" >
                                            <v-icon    color="pink">cancel</v-icon>
                                        </v-btn>
                                </template>
                                <span>Close</span>
                            </v-tooltip>
                        </v-list-item>

                        <v-divider class="my-0 py-0"></v-divider>

                        <applybadges_f />

                    </v-list>
                </v-menu>
            </v-badge>
        </div> -->



    </v-row>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import productcarddetails_snippet from './productcarddetails_snippet.vue'
import responsive from '../../mixins/responsive.vue'


export default {
    components: {
        productcarddetails_snippet,
    },
    mixins: [responsive],
    data() {
        return {
            token:localStorage.getItem('token'),
            products:[],
            carts:[],
            bagMenu: false,
            isLoaded: false,
        }
    },
    mounted() {
        this.getProducts();
        this.getCarts();

        setTimeout(() => {
            this.isLoaded = true
        }, 5000)

    },
    methods: {
        getProducts(){
            var _this = this;
            axios.get('/api/getProducts')
            .then(function (response) {
                _this.products = response.data.data;
            })
            .catch(function (error) {
            })
        },

        getCarts(){
            var _this = this;
            axios.get('/api/getCarts?token='+this.token+'&isForCurrentUser='+1)
            .then(function (response) {
                _this.carts = response.data.data;
            })
            .catch(function (error) {
            })
        },




        // getProducts(){

        //     var listProducts = this.getCartList
        //     var listProductsProductIds=[]

        //     listProducts.forEach(element => {
        //         listProductsProductIds.push(parseInt(element.productId))
        //     });

        //     var _this = this;
        //     axios.post('/api/getProductsWithProductIds', { 'products': listProductsProductIds})
        //     .then(function (response) {
        //         _this.products = _this.objectToArray(response.data.data);
        //     })
        //     .catch(function (error) {
        //     })
        // },
    },
    computed: {
        ...mapState([
        ]),
        ...mapGetters([
            'getCartList',  'getCompareList', 'getWishList'
        ]),
        getCartProductsInfo(){
            var  cartList= this.getCartList.map(function (row) {
                                return row.productId;
                            });
            return this.products.filter(function(product){
                return cartList.includes(product.productId);
            });
        },
    },
    watch: {
        getCartProductsInfo: function (newValue, oldValue) {
            // if (newValue.length!=oldValue.length && newValue.length>oldValue.length && oldValue.length>=0 && newValue.length>0 && newValue.length-oldValue.length==1 ) {
            if (newValue.length-oldValue.length==1 && this.isLoaded) {
                this.bagMenu= true
            }
            else{
                this.bagMenu=false
            }
        }
    },
}
</script>
