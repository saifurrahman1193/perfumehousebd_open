<template>
  <v-sheet class="white lighten-5" padless >

    <v-container class="flex black--text" >

      <v-container>
        <v-row class="text-uppercase">

            <v-col xs="12" sm="6" md="4"  cols="auto" light>
                <v-card-title primary-title>Latest</v-card-title>
                <v-list color="transparent">
                    <v-list-item-group >
                        <v-row v-for="(product, index) in latestProducts" :key="'latest-'+index">
                            <productcarddetails_snippet :product="product" :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false"></productcarddetails_snippet>
                        </v-row>
                    </v-list-item-group>

                </v-list>
            </v-col>

            <v-col xs="12" sm="6" md="4"  cols="auto" light>
                <hr style="width:83vw;" v-if="isXS">
                <v-card-title primary-title>Best Selling</v-card-title>
                <v-list color="transparent">
                    <v-list-item-group >
                        <v-row v-for="(product, index) in bestsellingProducts" :key="'bestsellingproduct-'+index">
                            <productcarddetails_snippet :product="product" :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false"></productcarddetails_snippet>
                        </v-row>
                    </v-list-item-group>
                </v-list>
            </v-col>

            <v-col xs="12" sm="6" md="4"  cols="auto" light>
                <hr style="width:83vw;" v-if="isXS">
                <v-card-title primary-title>Top Rated</v-card-title>
                <v-list color="transparent">
                    <v-list-item-group >
                        <v-row v-for="(product, index) in topratedProducts" :key="'topratedproduct-'+index">
                            <productcarddetails_snippet :product="product" :gotorouterviewwithloadprops="$route.name=='productdetails'? true : false"></productcarddetails_snippet>
                        </v-row>
                    </v-list-item-group>
                </v-list>
            </v-col>





        </v-row>


      </v-container>
    </v-container>
  </v-sheet>
</template>


<script>
import productcarddetails_snippet from './productcarddetails_snippet.vue'
import algorithms from './../../mixins/algorithms.vue'
import responsive from '../../mixins/responsive.vue'

export default {
    components: {
        productcarddetails_snippet,
    },
    mixins: [algorithms,responsive],
    mounted() {
        this.getLatestProducts();
        this.getBestsellingProducts();
        this.getTopratedProducts();
    },
    data() {
        return {
            latestProducts:[],
            bestsellingProducts:[],
            topratedProducts:[],
        };
    },
    methods: {
        getLatestProducts(){
            var _this = this;
            axios.get('/api/getLatestProducts')
            .then(function (response) {
                _this.latestProducts = response.data.data;
                _this.latestProducts = _this.arrayOfObjectSortDesc(_this.latestProducts,  'sellingPrice')
            })
            .catch(function (error) {
            })
        },

        getBestsellingProducts(){
            var _this = this;
            axios.get('/api/getBestsellingProducts')
            .then(function (response) {
                _this.bestsellingProducts = response.data.data;
                _this.bestsellingProducts = _this.arrayOfObjectSortDesc(_this.bestsellingProducts,  'sellingPrice')
            })
            .catch(function (error) {
            })
        },


        getTopratedProducts(){
            var _this = this;
            axios.get('/api/getTopratedProducts')
            .then(function (response) {
                _this.topratedProducts = response.data.data;
                _this.topratedProducts = _this.arrayOfObjectSortDesc(_this.topratedProducts,  'sellingPrice')
            })
            .catch(function (error) {
            })
        },



    },
    computed: {


    }
};
</script>


