<template>
    <v-app>
        <subheading_f></subheading_f>

        <v-sheet
            :class="isSMALT?'mt-6':'mt-12'"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >


                <v-row
                    justify="center" :class="isSMALT?'mt-0':'mt-12'"
                    >
                    <v-card-title class=" font-weight-bold " :class="isXS ?'h6' : 'display-1'">
                        <span  style="color:rgb(74, 74, 74)">Brands</span>
                    </v-card-title>


                </v-row>
                <v-toolbar-title class="subtitle text-center mx-auto" v-if="isLoaded">
                    <v-progress-circular
                            indeterminate
                            color="#C9151B"
                            size="70"
                            class="mx-auto"
                            >
                        </v-progress-circular>
                </v-toolbar-title>

                <v-row class="mx-1" align="center" justify="center">
                    <v-col
                        v-for="brand in brands || [] "
                        :key="brand.brandId"

                        cols="auto"
                        :width="isSMALT? 150: 250"
                        class="mx-0 px-0"
                        :style="isSMALT?'margin-right:4px !important;':'margin-right:10px !important;'"
                    >


                        <v-card
                            color="grey lighten-4"
                            :width="isSMALT? 150: 250"
                            :style="isSMALT? 'height:150px !important;' : 'height:150px !important;'"
                            @click="gotorouterview('/productlist?brandSlug='+brand.brandSlug);"
                        >
                            <v-img
                            class="white--text mx-auto"
                            :src="'/imageResize?url='+brand.logo+'&sizeX=150&sizeY=150' || '/uploads/no_image.png'"
                            height="100"
                            aspect-ratio contain
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

                            <v-card-title class="py-1 px-1" style="font-size:12px; line-height:10px;">{{brand.brand}}</v-card-title>
                            <h6 class="px-1" v-if="brand.totalProducts>0" style="font-size:11px; color: #222020a3;">{{brand.totalProducts}} products</h6>

                        </v-card>



                    </v-col>
                </v-row>



        </v-sheet>

        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f ></latestbestsellingtopratedproducts_f>
        <footer_f></footer_f>

    </v-app>
</template>


<script>

    import subheading_f from './subheading.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import footer_f from './footer.vue'

    import responsive from '../../mixins/responsive.vue'


    export default {
        mounted () {
            this.topFunction()
            this.getBrands();
        },
        components:{
           subheading_f,  latestviewsproducts_f, footer_f, latestbestsellingtopratedproducts_f
        },
        mixins: [responsive],
        data () {
            return {
                brands:[],
                isLoaded: false
            }
        },
        methods: {
            getBrands(){
                this.isLoaded=true
                var _this = this;
                axios.get('/api/getBrands')
                .then(function (response) {
                    _this.brands = response.data.data;
                    _this.isLoaded=false
                })
                .catch(function (error) {
                })
            },

            setSearchBrandProducts(brandSlug){
                this.gotorouterview('/productlist?brandSlug='+brandSlug)

                this.$store.commit('SET_SEARCH_BRAND_SLUG', brandSlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchBrandProductsWP')
            },


        },
        metaInfo: {
            // titleTemplate: '%s | Brands'
            titleTemplate: 'Brands'
        }



    }
</script>

<style scoped>
    .tree-node:hover{
        text-decoration : underline !important;
    }
</style>

