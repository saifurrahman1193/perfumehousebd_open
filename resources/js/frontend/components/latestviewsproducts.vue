<template>
    <div    >
        <v-skeleton-loader :width="isSMALT? 155: 250" :type="type" ref="skeleton" class="ml-5 "></v-skeleton-loader>

        <v-sheet
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
             class="pb-2"
             v-if="(products||[]).length>0"
             :style="isSMALT?'':'padding-left:7px'"
         >
            <v-row
                justify="center"
                v-if="products.length>0"
                >
                <v-card-title class=" font-weight-bold " :class="isXS ?'h6' : 'display-1'">
                    <span  style="color:rgb(74, 74, 74)">Latest Views</span>
                </v-card-title>
            </v-row>


            <!-- for 450 & greater than -->
            <!-- for 450 & greater than -->
            <v-slide-group
            active-class="success"
            show-arrows
            v-if="is450AGT"
            >
                <v-slide-item
                    v-for="(product, i) in products"
                    :key="'450-plus-'+i"
                    :width="isSMALT? 155: 250"
                    class="mx-1"
                    cols="auto"
                >
                    <v-card
                        color="grey lighten-4"
                        :width="isSMALT? 155: 250"
                        >
                        <productcarddetails_f :product="product||{}"  :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false"> </productcarddetails_f>
                    </v-card>

                </v-slide-item>
            </v-slide-group>
            <!-- for 450 & greater than -->
            <!-- for 450 & greater than -->



            <!-- for less than 450 -->
            <!-- for less than 450 -->
            <v-carousel
                cycle
                height="250"
                hide-delimiters
                v-else
            >
                <v-carousel-item
                v-for="(product, i) in products"
                :key="'450-less-'+i"
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
                        <productcarddetails_f :product="product||{}" :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false"/>
                    </v-card>


                    </v-row>
                </v-sheet>
                </v-carousel-item>
            </v-carousel>
            <!-- for less than 450 -->
            <!-- for less than 450 -->

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
            products:[],
            types:[],
            type: 'card',
        }
    },
    mounted() {
        this.getCurrentUserProductVisits();
        this.types = Object.keys(this.$refs.skeleton.rootTypes)

    },
    methods: {
        getCurrentUserProductVisits(){
            var _this = this;
            axios.get('/api/getCurrentUserProductVisits?token='+this.token)
            .then(function (response) {
                _this.products = _this.objectToArray(response.data.data);
                _this.products = (_this.products).filter((product) => (product||{}).productId>0)

                // _this.products = _this.arrayOfObjectSortDesc(_this.products,  'sellingPrice');
                // _this.products = _this.products.slice(0, 10)
                _this.type = ""
                _this.getDiscountCalculation()
            })
            .catch(function (error) {
                _this.type = ""
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
}
</script>
