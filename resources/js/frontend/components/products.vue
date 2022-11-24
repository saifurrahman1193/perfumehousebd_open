<template>
    <v-sheet
        class="mt-6"
        elevation="0"
        max-width="100%"
            color="grey lighten-5"
        >
        <v-row  align="center" justify="center">
            <v-card-title>
                <h1 class="heading_title_h1_h5">Products</h1>
            </v-card-title>
        </v-row>

        <v-row  :align="isSMALT? 'center': 'start'" :justify="isSMALT ? 'center':'start'">
            <v-col
                v-for="product in products"
                :key="product.productId"
                cols="auto"
                :width="isSMALT? 155: 250"
                class="mx-0 px-0"
            >
                <v-card
                    color="grey lighten-4"
                    :width="isSMALT? 155: 250"
                    class="mx-0"
                    >
                    <productcarddetails_f :product="product" ></productcarddetails_f>
                </v-card>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>



import productcarddetails_f from './productcarddetails.vue'

export default {
    components:{
           productcarddetails_f
        },
    mixins: [],
    data() {
        return {
            products:[],
        }
    },
    mounted() {
        this.getProducts();
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



    },
}
</script>
