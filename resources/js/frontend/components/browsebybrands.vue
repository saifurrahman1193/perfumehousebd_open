<template>
    <div  >
        <v-sheet
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >
            <v-row
                align="center"
                justify="center"
                >
                <v-card-title>
                    <h1 class="heading_title_h1_h5">Browse By Brands</h1>
                </v-card-title>
            </v-row>
            <v-skeleton-loader  :width="isSMALT?100:150"  :type="type" ref="skeleton" class="ml-5" ></v-skeleton-loader>

            <v-slide-group
            active-class="success"
            show-arrows
            >
                <v-slide-item
                    v-for="brand in brands"
                    :key="brand.brandId"
                    cols="auto"
                    :width="isSMALT?100:150"
                    class="px-0 mx-0"
                     :style="isSMALT?'margin-right:4px !important;':'margin-right:0px !important; margin-left:10px !important;'"
                >

                    <v-card
                        class="ma-4"
                        :height="isSMALT?140:150"
                        :width="isSMALT?100:150"
                        @click="gotorouterview('/productlist?brandSlug='+brand.brandSlug); "
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
            brands:[],
            types:[],
            type: 'card',
        }
    },
    mounted() {
        this.getBrands();
    },
    methods: {
        getBrands(){
            var _this = this;
            axios.get('/api/getBrands')
            .then(function (response) {
                _this.brands = response.data.data;
                _this.type = ""
            })
            .catch(function (error) {
            })
        },
    },
}
</script>

