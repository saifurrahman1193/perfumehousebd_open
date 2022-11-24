<template>
    <div   >
        <v-sheet
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
             :style="isSMALT?'padding-left:14px':'padding-left:20px'"
         >
            <v-row
                justify="center"
                >
                <v-card-title>
                    <h1 class="heading_title_h1_h5">Featured Products</h1>
                </v-card-title>
            </v-row>

            <v-skeleton-loader :width="isSMALT? 155: 250" :type="type" ref="skeleton" class="ml-5 "></v-skeleton-loader>

            <!-- for 450 & greater than -->
            <!-- for 450 & greater than -->
            <v-slide-group
            active-class="success"
            show-arrows
            v-if="is450AGT"
            >
                <v-slide-item
                    v-for="product in featuredproducts"
                    :key="product.productId"
                    :width="isSMALT? 155: 250"
                    class="mx-1"
                    cols="auto"
                >
                    <v-card
                        color="grey lighten-4"
                        :width="isSMALT? 155: 250"
                        >
                        <productcarddetails_f :product="product" />
                    </v-card>

                </v-slide-item>
            </v-slide-group>
            <!-- for sm a greater than -->
            <!-- for sm a greater than -->


            <!-- for less than 450 -->
            <!-- for less than 450 -->
            <v-carousel
                cycle
                height="250"
                hide-delimiters
                v-else
            >
                <v-carousel-item
                v-for="(product, i) in featuredproducts"
                :key="i"
                >
                <v-sheet
                    color="white"
                    height="100%"
                >
                    <v-row
                    class="fill-height"
                    align="center"
                    justify="center"
                    >

                    <v-card
                        color="grey lighten-4"
                        :width="isSMALT? 155: 250"
                        >
                        <productcarddetails_f :product="product" />
                    </v-card>


                    </v-row>
                </v-sheet>
                </v-carousel-item>
            </v-carousel>
            <!-- for less than 450 -->
            <!-- for less than 450 -->

            <!-- <v-row  :align="isSMALT? 'center': 'start'" :justify="isSMALT ? 'center':'start'">
                <v-col
                    v-for="product in featuredproducts"
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
            </v-row> -->
        </v-sheet>
    </div>
</template>

<script>

import productcarddetails_f from './productcarddetails.vue'
import algorithms from './../../mixins/algorithms.vue'
import responsive from '../../mixins/responsive.vue'

export default {
    components:{
            productcarddetails_f
        },
    mixins: [algorithms, responsive],
    data() {
        return {
            token: localStorage.getItem('token'),
            featuredproducts:[],
            types:[],
            type: 'card',
            isVisible: false,
        }
    },
    mounted() {
        this.getFeaturedProducts();
        this.types = Object.keys(this.$refs.skeleton.rootTypes)
    },
    methods: {
        getFeaturedProducts(){
            var _this = this;
            axios.get('/api/getFeaturedProducts')
            .then(function (response) {
                _this.featuredproducts = response.data.data;

                _this.featuredproducts = _.shuffle(_this.arrayOfObjectSortDesc(_this.featuredproducts,  'sellingPrice'))

                // _this.featuredproducts.slice(0, 5)

                _this.type = ""
                _this.getDiscountCalculation()
            })
            .catch(function (error) {
            })
        },
        getDiscountCalculation(){
            var _this = this;
            axios.post('/api/getDiscountCalculation', {token: this.token, products: this.featuredproducts})
            .then(function (response) {
                _this.featuredproducts=response.data.data
            })
            .catch(function (error) {
            })
        },
    },
}
</script>
