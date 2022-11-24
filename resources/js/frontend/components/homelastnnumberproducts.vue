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


            <v-row    :align="isSMALT? 'center': 'start'" :justify="isSMALT ? 'center':'start'">
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

            <v-row v-if="rangeStart<=11 && products.length>0">
                <v-spacer></v-spacer>
                <v-btn :small="isSMALT ? true : false" color="red accent-4" class="white--text " :class="isXS? 'mr-6' : 'mr-12'" @click="rangeStart+=10; getLastNNumberProducts()"

                :loading="progress_mode"
                :disabled="progress_mode"

                >See More..</v-btn>
            </v-row>

        </v-sheet>



    </div>
</template>

<script>
import productcarddetails_f from './productcarddetails.vue'
import algorithms from './../../mixins/algorithms.vue'
import responsive from '../../mixins/responsive.vue'


export default {
    components:{productcarddetails_f },
    mixins: [algorithms, responsive],
    data() {
        return {
            token: localStorage.getItem('token'),
            products:[],
            types:[],
            type: 'card',
            numberofProducts: 10,
            rangeStart: 1,

            progress_mode: false,

        }
    },
    mounted() {
        this.types = Object.keys(this.$refs.skeleton.rootTypes)
        this.getLastNNumberProducts()
    },
    methods: {
        getLastNNumberProducts(){
            this.progress_mode=true

            var _this = this;
            axios.get('/api/getLastNNumberProducts/'+this.numberofProducts+'/'+this.rangeStart)
            .then(function (response) {
                var arr = _this.objectToArray(response.data.data);

                arr = _.shuffle(_this.arrayOfObjectSortDesc(arr, 'sellingPrice'))

                // console.log(arr)

                _this.products = _this.products.concat(arr)

                _this.type = ""
                _this.getDiscountCalculation()
                _this.progress_mode=false

            })
            .catch(function (error) {
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
    },
}
</script>
