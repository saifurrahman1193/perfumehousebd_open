<template>
    <v-app>
        <subheading_f></subheading_f>
        <advertisement_slider_f></advertisement_slider_f>
        <featuredproducts_f></featuredproducts_f>
        <!-- <homerandomproducts_f></homerandomproducts_f> -->
        <homelastnnumberproducts_f></homelastnnumberproducts_f>
        <browsebybrands_f></browsebybrands_f>
        <!-- <ourservices_f/> -->
        <!-- <contactus_f/> -->
        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f ></latestbestsellingtopratedproducts_f >
        <footer_f></footer_f>




    </v-app>
</template>


<script>
    import subheading_f from './subheading.vue'
    // import products_f from './products.vue'
    import browsebybrands_f from './browsebybrands.vue'
    import featuredproducts_f from './featuredproducts.vue'
    // import ourservices_f from './ourservices.vue'
    // import contactus_f from './contactus.vue'
    import advertisement_slider_f from './advertisement_slider.vue'
    // import homerandomproducts_f from './homerandomproducts.vue'
    import homelastnnumberproducts_f from './homelastnnumberproducts.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import footer_f from './footer.vue'

    // import seoMixins from './../../mixins/seo.vue'

    export default {
        components:{
            subheading_f, advertisement_slider_f,  browsebybrands_f, featuredproducts_f,homelastnnumberproducts_f, latestviewsproducts_f, footer_f, latestbestsellingtopratedproducts_f
            // ourservices_f, advertisement_slider_f, contactus_f, products_f,
        },
        mixins: [],
        mounted() {
            this.topFunction()

            if (this.$route.query.token) {
                localStorage.setItem('token', this.$route.query.token);
            }

        },
        metaInfo: {
            titleTemplate: 'Perfume House BD'
        }

    }
</script>
