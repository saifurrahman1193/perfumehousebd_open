<template>
    <div  >
        <v-sheet
            class="mt-6"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >
            <v-row
                class=" mt-12"
                justify="center"
                >
                <v-card-title class="display-1 font-weight-bold hidden-xs-only" >
                    <span  style="color:rgb(74, 74, 74)">Related Products</span>
                </v-card-title>
                <v-card-title class="h6 font-weight-bold hidden-sm-and-up" >
                    <span  style="color:rgb(74, 74, 74)">Related Products</span>
                </v-card-title>
            </v-row>

            <v-skeleton-loader :width="isSMALT? 155: 250" :type="type" ref="skeleton" class="ml-5 "></v-skeleton-loader>

            <v-slide-group
            active-class="success"
            show-arrows
            >
                <v-slide-item
                    v-for="product in relatedproducts"
                    :key="product.productId"
                    xs="12"
                    sm="6"
                    md="3"
                    cols="auto"
                    :width="isSMALT? 155: 250"
                    :class="isSMALT?'mx-1':'mx-2'"
                >
                    <v-card
                        color="grey lighten-4"
                        :width="isSMALT? 155: 250"
                    >
                        <productcarddetails_f :product="product" :gotorouterviewwithloadprops="true"></productcarddetails_f>
                    </v-card>

                </v-slide-item>


            </v-slide-group>
        </v-sheet>



    </div>
</template>

<script>

import productcarddetails_f from './productcarddetails.vue'
import responsive from '../../mixins/responsive.vue'

export default {
    components:{
            productcarddetails_f
        },
    mixins: [responsive],
    data() {
        return {
            token: localStorage.getItem('token'),
            relatedproducts:[],
            types:[],
            type: 'card',
        }
    },
    mounted() {
        this.getRelatedProducts();
        this.types = Object.keys(this.$refs.skeleton.rootTypes)
    },
    methods: {
        getRelatedProducts(){
            var _this = this;
            axios.get('/api/getRelatedProducts')
            .then(function (response) {
                _this.relatedproducts = response.data.data
                _this.type = ""
                _this.getDiscountCalculation()
            })
            .catch(function (error) {
            })
        },
        getDiscountCalculation(){
            var _this = this;
            axios.post('/api/getDiscountCalculation', {token: this.token, products: this.relatedproducts})
            .then(function (response) {
                _this.relatedproducts=response.data.data
            })
            .catch(function (error) {
            })
        },
    },
}
</script>
