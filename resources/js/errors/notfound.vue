<template>
    <v-app>
        <subheading_f></subheading_f>
        <v-container
            class="text-center"
            fill-height
            style="height: calc(100vh - 58px);"
        >
            <v-row align="center">
            <v-col>
                <h1 class=" orange--text" :class="isXS? 'display-1' : 'display-2'">Whoops, 404</h1>

                <p>The page you were looking for does not exist</p>

                <v-btn
                to="/"
                color="#C9151B"
                outlined
                >
                Go Home!
                </v-btn>
            </v-col>
            </v-row>
        </v-container>
        <footer_f/>
    </v-app>

</template>


<script>
    import subheading_f from './../frontend/components/subheading.vue'
    import footer_f from './../frontend/components/footer.vue'

    export default {
        components:{
            subheading_f, footer_f
        },
        mounted() {
            document.title = "404 Page Not Found"
        },
        created () {
            this.$ssrContext && this.$ssrContext.res.status(404)
        },
        data() {
            return {

            }
        },
        methods: {



        },
        computed: {

        }
    }
</script>
