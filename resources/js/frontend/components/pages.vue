<template>
    <v-app>
        <subheading_f></subheading_f>
            <v-sheet
                class="mx-auto mt-12"
                elevation="0"
                width="100%"
                color="grey lighten-5"
            >
                   <v-content>
                    <v-card elevation="1" class="mx-3">
                        <v-card-title primary-title >
                            <v-spacer></v-spacer>
                            {{f_dynamic_page_info.pageTitle }}
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-card-text>
                            <v-toolbar-title class="display-1 mt-2 mx-auto text-center" >  </v-toolbar-title>
                            <p v-html="f_dynamic_page_info.pageDesc"></p>
                        </v-card-text>
                        </v-card>
                    </v-content>
            </v-sheet>

        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f ></latestbestsellingtopratedproducts_f>
        <footer_f></footer_f>
    </v-app>
</template>


<script>
    import subheading_f from './subheading.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import footer_f from './footer.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'

    import { mapState } from 'vuex'


    export default {
        data() {
            return {
                pageSlug: this.$route.params.pageSlug,
            }
        },
        mounted() {
            this.topFunction()

            this.setPageData(this.pageSlug)
        },
        components:{
            subheading_f,  footer_f, latestviewsproducts_f, latestbestsellingtopratedproducts_f
        },
        methods: {
            setPageData(pageSlug){
                this.$store.dispatch('setDynamicPageInfo', {pageSlug:pageSlug})
            }
        },
        computed: {
            ...mapState([
                'f_dynamic_page_info',
            ]),
        },
    }
</script>
