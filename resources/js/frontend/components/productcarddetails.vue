<template>
    <div :style="isSMALT? 'height:250px !important;' : 'height:275px !important;'">

        <v-hover v-slot:default="{ hover }">
            <v-img
                :aspect-ratio="16/9"
                :src="'/imageResize?url='+product.productPicturePath+(isSMALT? '&sizeX=150&sizeY=150': '&sizeX=200&sizeY=200') || '/uploads/no_image.png'"
                contain
                :width="isSMALT? 150: 250"
            >
                <template v-slot:placeholder>
                    <v-row
                    class="fill-height ma-0"
                    align="center"
                    justify="center"
                    >
                        <v-progress-circular  color="#C9151B"></v-progress-circular>
                        <!-- indeterminate -->
                    </v-row>
                </template>

                <v-expand-transition

                >
                    <div  v-if="hover"
                    class="d-flex transition-fast-in-fast-out black  v-card--reveal display-1 white--text mx-auto my-auto"
                    style="height: 100%;"

                    >
                        <v-row>
                            <v-col>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn color="#fff" rounded class="float-right"  style="opacity: 1;"
                                            @click="imageZoom(product.productPicturePath)"
                                            v-on="on"
                                            v-bind="btnSize"
                                        >
                                            <v-icon
                                            color="black"
                                            >search</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Quick View</span>
                                </v-tooltip>
                            </v-col>
                            <v-col>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn color="#fff" rounded class="float-left" style="opacity: 1;"
                                            @click=" gotorouterviewwithloadprops!=true? gotorouterview('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId):  gotorouterviewwithload('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId)"
                                            v-on="on"
                                            v-bind="btnSize"
                                        >
                                            <v-icon
                                            color="black"
                                            >more_horiz</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Product Details</span>
                                </v-tooltip>
                            </v-col>
                        </v-row>
                    </div>



                </v-expand-transition>
            </v-img>
        </v-hover>
        <v-card-text
            class="pt-6"
            style="position: relative;"

        >
                <v-row>
                        <v-btn
                            absolute
                            :color="isAddedToWishlist(product.productId) ? '#C9151B' : 'white'"
                            :class="isAddedToWishlist(product.productId) ? 'white--text' : 'grey--text'"
                            :style="getCartProductQty(product.productId)>=1 || !(product.sellingPrice>0) ? 'margin-right:45px;' : 'margin-right:90px;'"
                            fab
                            :size="isSMALT ? 'small' : 'large'"
                            right
                            top
                            :width="isSMALT?25:40"
                            :height="isSMALT?25:40"
                            >
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon small  v-if="isAddedToWishlist(product.productId)" @click="removeCurrentUserProductFromWishlist(product.productId)" v-on="on">favorite</v-icon>
                                        <v-icon small v-else  @click="addCurrentUserProductToWishlist(product.productId)" v-on="on">favorite_border</v-icon>
                                    </template>
                                    <span  v-if="isAddedToWishlist(product.productId)">Remove from wishlist</span>
                                    <span v-else>Add to wishlist</span>
                                </v-tooltip>
                        </v-btn>

                        <v-btn
                            absolute
                            :color="isAddedToCompare(product.productId) ? '#C9151B' : 'white'"
                            :class="isAddedToCompare(product.productId) ? 'white--text' : 'grey--text'"
                            :style="getCartProductQty(product.productId)>=1 || !(product.sellingPrice>0) ? 'margin-right:0px;' : 'margin-right:45px;'"
                            fab
                            :size="isSMALT ? 'small' : 'large'"
                            right
                            top
                            :width="isSMALT?25:40"
                            :height="isSMALT?25:40"

                            >
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon small  v-if="isAddedToCompare(product.productId)" @click="removeFromCompareList(product.productId)" v-on="on">compare</v-icon>
                                        <v-icon small  v-else @click="addToCompareList(product.productId)" v-on="on">compare</v-icon>
                                    </template>
                                    <span v-if="isAddedToCompare(product.productId)">Remove from compare list</span>
                                    <span  v-else >Add to compare list</span>
                                </v-tooltip>
                            </v-btn>
                        <v-btn
                        absolute
                        class="#C9151B"

                        fab
                        :size="isSMALT ? 'small' : 'large'"
                        right
                        top
                        @click="addQtyToGuestCart(product.productId)"
                        v-if="getCartProductQty(product.productId)<1 && product.sellingPrice && product.outOfStock==0"
                        :width="isSMALT?25:40"
                        :height="isSMALT?25:40"

                        >
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon small v-on="on">add_shopping_cart</v-icon>
                                </template>
                                    <span>Add to cart</span>
                            </v-tooltip>
                        </v-btn>
                </v-row>







            <div class="font-weight-light grey--text text--darken-1 subtitle-2 mb-0"
            @click=" gotorouterviewwithloadprops!=true? gotorouterview('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId):  gotorouterviewwithload('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId)"
            >
                <span v-if="productName.length<=48" :style="isSMALT? 'font-size: 10px; color: black;': ''" class="show_hand hover_apple">{{productName}}</span>

                <v-tooltip bottom v-else color="white black--text">
                    <template v-slot:activator="{ on, attrs }" >
                        <span
                        v-bind="attrs"
                        v-on="on"
                        :style="isSMALT? 'font-size: 10px; color: black;': ''"
                        >{{productName.substr(0,47)+'...'}}</span>
                    </template>
                    <span>{{productName}}</span>
                </v-tooltip>

                <v-rating
                    background-color="#C9151B"
                    color="#C9151B"
                    dense
                    readonly
                    v-model="product.rating"
                    half-increments
                    v-if="product.rating"
                    small
                    style="line-height:0px !important"
                >
                </v-rating>

            </div>


            <v-row class="py-0">
                <v-col cols="auto" md="6"  sm="12" xs="12" class="py-0"
                @click=" gotorouterviewwithloadprops!=true? gotorouterview('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId):  gotorouterviewwithload('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId)"
                >
                    <h3 style="color: #C9151B" class="headline font-weight-light  mb-0 " :class="isSMALT? 'subtitle-1': ''"  v-if="product.sellingPrice && product.discountAmount>0">৳ {{product.sellingPrice-product.discountAmount}} <h6><del>৳ {{product.sellingPrice}}</del></h6></h3>
                    <h3 style="color: #C9151B" class="headline font-weight-light  mb-0 " :class="isSMALT? 'subtitle-1': ''"  v-else-if="product.sellingPrice && product.discountPercent>0">৳ {{product.sellingPrice - (product.sellingPrice*(product.discountPercent/100))}} <h6><del>৳ {{product.sellingPrice}}</del></h6></h3>
                    <h3 style="color: #C9151B" class="headline font-weight-light  mb-0 " :class="isSMALT? 'subtitle-1': ''"  v-else-if="product.sellingPrice">৳ {{product.sellingPrice}} </h3>
                </v-col>
                <v-col cols="auto" md="6" sm="12" xs="12" class="py-0" v-if="!getCartProductQty(product.productId)>0 && product.sellingPrice && product.outOfStock!=1">
                    <v-chip class=" float-right"  small  outlined color="red red--text" text-color="white"
                        @click="addQtyToGuestCart(product.productId)"
                        >Add To Bag</v-chip>
                </v-col>
                <v-col cols="auto" md="6" sm="12" xs="12" class="py-0">
                    <v-chip class="float-right"  small v-if="!product.sellingPrice ||  product.outOfStock==1" color="red" text-color="white">Out of stock</v-chip>

                    <v-chip
                        absolute
                        color="#C9151B"
                        class="white--text radius pa-0"
                        v-if="getCartProductQty(product.productId)>=1 && product.sellingPrice"
                        elevation="0"
                        small
                        >
                        <v-btn class="mx-1" icon dark small @click="subtractProductQtyFromCart(product.productId)"><v-icon>remove</v-icon></v-btn>
                        {{getCartProductQty(product.productId)}}
                        <v-btn class="mx-1" icon dark small @click="addQtyToGuestCart(product.productId)"><v-icon>add</v-icon></v-btn>
                    </v-chip>
                </v-col>
            </v-row>

        </v-card-text>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>


    </div>
</template>

<script>

import addtocartactionsMethodMixin from './../../mixins/addtocartactions.vue'

import addtocomparelistactionsMethodMixin from './../../mixins/addtocomparelistactions.vue'
import addtowishlistactionsMethodMixin from './../../mixins/addtowishlistactions.vue'

// zoom
    import zoom_modal from './zoom/zoom.vue'
    import zoom_mixin from './zoom/zoomMixins.vue'

import responsive from '../../mixins/responsive.vue'



export default {
    components:{
           zoom_modal,
        },
    mixins: [ zoom_mixin, addtocartactionsMethodMixin, addtocomparelistactionsMethodMixin, addtowishlistactionsMethodMixin, responsive],
    data() {
        return {
            productName: (this.product.productName+' '+(this.product.qty||'')+' '+(this.product.uom||'')),
        }
    },
    mounted() {
    },
    methods: {


    },
    props: ["product", "gotorouterviewwithloadprops"],
    computed: {
        btnSize: function(){
            return this.isSMALT?{'x-small':true} : {};
        }
    },
}
</script>



<style scoped>
    .v-card--reveal {
        align-items: center;
        bottom: 0;
        justify-content: center;
        opacity: .5;
        position: absolute;
        width: 100%;
    }
</style>
