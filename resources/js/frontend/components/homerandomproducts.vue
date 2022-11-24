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
                <v-card-title class=" font-weight-bold " :class="isXS ?'h6' : 'display-1'">
                    <span  style="color:rgb(74, 74, 74)">Products</span>
                </v-card-title>
            </v-row>

            <v-skeleton-loader :width="isSMALT? 155: 250" :type="type" ref="skeleton" class="ml-5 "></v-skeleton-loader>


            <v-row >
                <v-col
                    v-for="product in products || [] "
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
                        <productcarddetails_f :product="product" ></productcarddetails_f>
                    </v-card>
                </v-col>
            </v-row>

        </v-sheet>



    </div>
</template>

<script>
import productcarddetails_f from './productcarddetails.vue'


export default {
    components:{productcarddetails_f },
    mixins: [],
    data() {
        return {
            token: localStorage.getItem('token'),
            products:[],
            types:[],
            type: 'card',
        }
    },
    mounted() {
        this.types = Object.keys(this.$refs.skeleton.rootTypes)
        this.getRandomProducts()
    },
    methods: {
        getRandomProducts(){
            var _this = this;
            axios.get('/api/getRandomProducts/16')
            .then(function (response) {
                _this.products = response.data.data;
                _this.type = ""
                _this.getDiscountCalculation()
            })
            .catch(function (error) {
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
