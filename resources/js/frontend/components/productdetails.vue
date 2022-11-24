<template>
    <v-app>
      <subheading_f></subheading_f>

      <v-sheet
          class="mx-auto mt-12  mb-6"
          elevation="0"
          width="100%"
          color="white"
      >
        <v-row class="mt-12 hidden-sm-and-down"></v-row>
        <v-row>
            <v-col cols="12" xs="12" sm="6" md="6">
                <v-card class="mx-auto" >
                    <v-card-text class="pa-0">
                        <v-img
                        :src="getThisProductPicture(productPictureId)"
                        contain
                        aspect-ratio="1"
                        height="250"
                        class="image-hover-cursor-change"
                        @click="imageZoom(getThisProductPicture(productPictureId))"
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
                    </v-card-text>
                    <v-card-actions>
                        <v-row>
                            <v-col cols="12" sm="12" >
                                        <v-row>
                                            <v-col
                                            v-for="productpicture in getThisProductPictures(productId)"
                                            :key="productpicture.productPictureId"
                                            class="d-flex child-flex"
                                            cols="2"
                                            >
                                            <v-card flat tile class="d-flex">
                                                <v-img
                                                :src="productpicture.productPicturePath"
                                                aspect-ratio="1"
                                                class="grey lighten-2"
                                                @click="setProductPicture(productpicture.productPictureId)"
                                                :class="productpicture.productPictureId==productPictureId? 'pictureSelected': 'pictureNotSelected'"
                                                style="background:rgba(0,0,0,0) !important; border-color:#C9151B !important;"
                                                min-width="50"
                                                min-height="50"
                                                contain
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
                                            </v-card>
                                            </v-col>
                                        </v-row>
                            </v-col>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" xs="12" sm="6" md="6">
                <v-card
                    class="mx-auto"
                >
                    <v-card-title class="pb-0 "  style="word-break: break-word !important;">
                        <h2 class="display-1 hidden-sm-and-down" style="word-break: break-word !important;">{{product.productName}}</h2>
                        <h5 class="d-none d-sm-flex d-md-none" style="word-break: break-word !important;">{{product.productName}}</h5>
                        <h6 class="hidden-sm-and-up" style="word-break: break-word !important;">{{product.productName}}</h6>
                    </v-card-title>

                    <v-card-text>
                        <v-row >
                            <v-col class="pt-0">

                                <span class="title theme_font_color"  v-if="product.sellingPrice && product.discountAmount>0">৳ {{product.sellingPrice-product.discountAmount}} <h6><del>৳ {{product.sellingPrice}}</del></h6></span>
                                <span class="title theme_font_color"  v-else-if="product.sellingPrice && product.discountPercent>0">৳ {{product.sellingPrice - (product.sellingPrice*(product.discountPercent/100))}} <h6><del>৳ {{product.sellingPrice}}</del></h6></span>
                                <span class="title theme_font_color"  v-else-if="product.sellingPrice">৳ {{product.sellingPrice}}</span>


                                <countdown_f :countdowndate="product.expireDate" class="py-1"/>
                                <div class="theme_font_color subtitle-1 py-1">{{product.discountReason}}</div>


                                <!-- <p class="ma-0 pb-2 font-weight-bold" v-if="product.sku">SKU: {{product.sku}}</p> -->
                                <p class="ma-0" v-if="product.qty">Capacity: {{product.qty+' '+product.uom}}</p>
                                <p class="ma-0" v-if="product.brand">Brand: {{product.brand}}</p>
                                <p class="ma-0" v-if="product.gender">Gender: {{product.gender}}</p>
                                <p class="ma-0" v-if="product.productType">Product Type: {{product.productType}}</p>
                                <p class="ma-0" v-if="product.applicationArea">Application Area: {{product.applicationArea}}</p>
                                <p class="ma-0" v-if="product.skinType">Skin Type: {{product.skinType}}</p>
                                <p class="ma-0" v-if="product.formulation">Formulation: {{product.formulation}}</p>
                                <p class="ma-0" v-if="product.country">Origin: {{product.country}}</p>

                            </v-col>
                            <v-col class="floa-right">

                                    <v-list dense class="float-right pa-0">
                                        <v-list-item dense class="pa-0" v-if="!(product.sellingPrice>0) || product.outOfStock==1">
                                            <v-chip   small  color="red" text-color="white"

                                            >Out of stock</v-chip>
                                            <!-- v-if="!(product.sellingPrice>0) || product.outofStock==1" -->
                                        </v-list-item>
                                        <v-list-item dense class="pa-0">
                                            <v-rating
                                                background-color="orange lighten-3"
                                                color="#C9151B"
                                                medium
                                                dense
                                                readonly
                                                v-model="product.rating"
                                                half-increments
                                                v-if="product.rating"
                                                class="float-right"
                                            >
                                            </v-rating>
                                        </v-list-item>
                                    </v-list>
                            </v-col>
                        </v-row>



                        <p class="ma-0 pt-2" v-if="product.shortDescription" v-html="product.shortDescription"></p>


                    </v-card-text>

                    <v-divider class="mx-4"></v-divider>


                    <v-card-actions>

                        <v-btn
                            class="white--text"
                            color="#C9151B"
                            @click="addQtyToGuestCart(product.productId)"
                            v-if="getCartProductQty(product.productId)<1 && product.sellingPrice && product.outOfStock!=1"
                        >
                            Add to Bag
                        </v-btn>

                        <v-chip
                            absolute
                            color="#C9151B"
                            class="white--text radius"
                            v-if="getCartProductQty(product.productId)>=1 && product.sellingPrice"

                            >
                            <v-btn  text dark  @click="subtractProductQtyFromCart(product.productId)"><v-icon>remove</v-icon></v-btn>
                            {{getCartProductQty(product.productId)}} item<span v-if="getCartProductQty(product.productId)>1">s&nbsp;</span> in bag
                            <v-btn text dark  @click="addQtyToGuestCart(product.productId)"><v-icon>add</v-icon></v-btn>
                        </v-chip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn class="ma-2" text icon  v-on="on">
                                    <v-icon color="#C9151B" v-if="isAddedToWishlist(product.productId)" @click="removeCurrentUserProductFromWishlist(product.productId)">favorite</v-icon>
                                    <v-icon v-else  @click="addCurrentUserProductToWishlist(product.productId)">favorite_border</v-icon>
                                </v-btn>
                            </template>
                            <span  v-if="isAddedToWishlist(product.productId)">Remove from wishlist</span>
                            <span v-else>Add to wishlist</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn class="ma-2" text icon v-on="on" >
                                    <v-icon color="#C9151B" v-if="isAddedToCompare(product.productId)" @click="removeFromCompareList(product.productId)">compare</v-icon>
                                    <v-icon  v-else @click="addToCompareList(product.productId)">compare</v-icon>
                                </v-btn>
                            </template>
                            <span v-if="isAddedToCompare(product.productId)">Remove from compare list</span>
                            <span  v-else >Add to compare list</span>
                        </v-tooltip>


                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

            <v-card >
                <v-tabs
                background-color="transparent"
                grow
                color="#C9151B"
                v-if="isReviewLoaded"
                >
                    <v-tab >Description</v-tab>
                    <v-tab >Reviews</v-tab>


                    <v-tab-item >
                        <v-card flat>
                            <v-card-text>
                                <span v-html="product.description"></span>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item >
                        <v-card flat>
                            <v-card-text>
                                <reviews_f :reviews="reviews" :reviews_p="reviews_p"  :review_current_page="review_current_page" :review_length="review_length" :productId="productId" ></reviews_f>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>

                </v-tabs>
            </v-card>



        <relatedproducts_f></relatedproducts_f>


        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>





      </v-sheet>

    <v-spacer></v-spacer>
    <latestviewsproducts_f gotorouterviewwithloadprops="true" ></latestviewsproducts_f>
    <latestbestsellingtopratedproducts_f gotorouterviewwithloadprops="true"></latestbestsellingtopratedproducts_f>
    <footer_f></footer_f>


    </v-app>
</template>

<script>

import addtocartactionsMethodMixin from '../../mixins/addtocartactions.vue'
import addtocomparelistactionsMethodMixin from '../../mixins/addtocomparelistactions.vue'
import addtowishlistactionsMethodMixin from '../../mixins/addtowishlistactions.vue'

import subheading_f from './subheading.vue'
import relatedproducts_f from './relatedproducts.vue'
import reviews_f from './reviews.vue'
import countdown_f from './countdown.vue'

import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'


import latestviewsproducts_f from './latestviewsproducts.vue'
import footer_f from './footer.vue'

 // zoom
    import zoom_modal from './zoom/zoom.vue'
    import zoom_mixin from './zoom/zoomMixins.vue'

import responsive from '../../mixins/responsive.vue'

  export default {
    components:{
        subheading_f, relatedproducts_f, zoom_modal, reviews_f, latestviewsproducts_f,  footer_f, latestbestsellingtopratedproducts_f, countdown_f
    },
    mixins: [ addtocartactionsMethodMixin, addtocomparelistactionsMethodMixin, addtowishlistactionsMethodMixin, zoom_mixin, responsive],
    data() {
        return {
                token:localStorage.getItem('token'),
                product:{},
                productId: this.$route.params.productId,
                productpictures:[],
                productPictureId:'',
                tab: null,
                reviews:[],
                reviews_p:[],
                review_current_page:'',
                review_length:'',
                isReviewLoaded:false,
        }
    },
    mounted() {
        this.getSingleProductPictures();
        this.topFunction()
        this.getProductReviews()
        this.getProductReviewsWithPaginate()
        this.getProductAllData(this.productId);
        this.addProductVisit();
    },

    methods: {
        addProductVisit(){
            axios.post('/api/addProductVisit', {productId:this.productId, token: this.token})
            .then(function (response) {
            })
            .catch(function (error) {
            })
        },


        getProductAllData(productId){
            var _this = this;
            axios.get('/api/getProductAllData/'+this.productId)
            .then(function (response) {
                _this.product = response.data.data;
                _this.getDiscountCalculation();
            })
            .catch(function (error) {
            })
        },
        getSingleProductPictures(){
            var _this = this;
            axios.get('/api/getSingleProductPictures/'+this.productId)
            .then(function (response) {
                _this.productpictures = _this.objectToArray(response.data.data);
                _this.productpictures =_this.productpictures.filter(function(productpicture){
                                            return productpicture.productId == productId
                                        }).map(function(productpicture) {
                                            return productpicture.productPicturePath
                                        })

            })
            .catch(function (error) {
            })
        },
        getThisProductPictures( productId ){

            var _this = this;
            return this.productpictures.filter(function(productpicture){
                if (!_this.productPictureId &&  productpicture.productId ==productId  && productpicture.isDefault == 1) {
                    _this.productPictureId = productpicture.productPictureId
                }
                return productpicture.productId == productId
            })

        },
        getThisProductPicture( productPictureId ){
            let picturePath = '';
            this.productpictures.forEach(productpicture => {
                if (productpicture.productPictureId == productPictureId) {

                    picturePath = productpicture.productPicturePath
                }
            });
            return picturePath;
        },
        setProductPicture(productPictureId){
            this.productPictureId = productPictureId;
        },

        getProductReviews(){
            var _this = this;
            axios.get('/api/getProductReviews/'+this.productId)
            .then(function (response) {
                _this.reviews = response.data.data;
                _this.isReviewLoaded=true
            })
            .catch(function (error) {
            })
        },

        getProductReviewsWithPaginate(page=1){
            var _this = this;
            axios.get('/api/getProductReviewsWithPaginate/'+this.productId+'?page='+page)
            .then(function (response) {
                _this.reviews_p = response.data.data.data;
                _this.review_current_page =  response.data.data.current_page
                _this.review_length =  response.data.data.last_page

            })
            .catch(function (error) {
            })
        },
        getDiscountCalculation(){
            var _this = this;
            axios.post('/api/getDiscountCalculation', {token: this.token, products: [this.product]})
            .then(function (response) {
                _this.product=response.data.data[0]
            })
            .catch(function (error) {
            })
        },
        getProductName(){
            return this.$route.params.productName;
        }


    },

    computed: {


        getProductQty: function () {
            var _this = this;
            var qty = 0;
            _this.guestCart.forEach(guestCartProduct => {
                if (guestCartProduct.productId == _this.product.productId ) {
                    qty=guestCartProduct.qty
                }
            });

            return qty;
        },

    },
    metaInfo() {
        return {
            // override the parent template and just use the above title only
            // titleTemplate: null
            // titleTemplate: '%s | '+this.$route.params.productName,
            titleTemplate: this.product.productName,
            meta: [
                { property: 'og:title', content: this.product.productName },
                { property: 'twitter:title', content: this.product.productName },
                { property: 'og:description', content: this.product.productName+', Brand: '+this.product.brand+', Gender: '+this.product.gender+', Categories: '+this.product.categories+',  description : '+this.product.description },
                { property: 'twitter:description', content: this.product.productName+', Brand: '+this.product.brand+', Gender: '+this.product.gender+', Categories: '+this.product.categories+',  description : '+this.product.description },
                { name: 'description', content: this.product.productName+', Brand: '+this.product.brand+', Gender: '+this.product.gender+', Categories: '+this.product.categories+',  description : '+this.product.description },
                { property: 'og:image', content: window.location.origin+this.product.productPicturePath },
                { property: 'twitter:image', content: window.location.origin+this.product.productPicturePath },
                { property: 'twitter:image:src', content: window.location.origin+this.product.productPicturePath },
            ]
        }
    }

  }
</script>

<style>
    .pictureNotSelected{
        cursor:pointer;
    }
    .pictureSelected{
        cursor:pointer;
        border: 1px solid;
        border-color:  #C9151B !important;
        background:  #C9151B !important;
    }

    .zoom:hover {
        transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }

</style>


