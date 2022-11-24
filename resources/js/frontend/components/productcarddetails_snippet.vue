<template>
    <v-list-item >
        <v-list-item-icon class="mx-0">
            <v-img
                :aspect-ratio="16/9"
                :src="'/imageResize?url='+product.productPicturePath+(isSMALT? '&sizeX=50&sizeY=50': '&sizeX=80&sizeY=80') || '/uploads/no_image.png'"
                contain
                :width="isSMALT? 50: 80"
                @click="imageZoom(product.productPicturePath)"
                style="cursor: zoom-in;"
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
        </v-list-item-icon>
        <v-list-item-content
        @click=" gotorouterviewwithloadprops!=true? gotorouterview('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId):  gotorouterviewwithload('/productdetails/'+string_to_slug(product.productName)+'/'+product.productId)"
        >
            <v-list-item-title v-text="product.productName+' '+(product.qty||'')+(product.uom||'')"

            :style="`cursor: pointer; white-space:normal;${(isSmall ? 'font-size : 9px !important;' : '')}`"
            class="subtitle-2 font-weight-bold grey--text text--darken-1"

            ></v-list-item-title>
            <!-- <p
            class="subtitle-2 font-weight-light grey--text text--darken-1"
            >{{(product.qty||"")+" "+(product.uom||"")}}</p> -->

            <v-rating
                background-color="#C9151B"
                color="#C9151B"
                medium
                dense
                readonly
                v-model="product.rating"
                half-increments
                v-if="product.rating"
            >
            </v-rating>

        </v-list-item-content>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>
    </v-list-item>
</template>

<script>

import addtocartactionsMethodMixin from './../../mixins/addtocartactions.vue'

import addtocomparelistactionsMethodMixin from './../../mixins/addtocomparelistactions.vue'
import addtowishlistactionsMethodMixin from './../../mixins/addtowishlistactions.vue'
import responsive from '../../mixins/responsive.vue'

// zoom
    import zoom_modal from './zoom/zoom.vue'
    import zoom_mixin from './zoom/zoomMixins.vue'



export default {
    components:{
           zoom_modal,
        },
    mixins: [ zoom_mixin, responsive],
    data() {
        return {

        }
    },
    mounted() {
    },
    methods: {


    },
    props: ["product", "gotorouterviewwithloadprops", "isSmall"],
    computed: {

    },
}
</script>



