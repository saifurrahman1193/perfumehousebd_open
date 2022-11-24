<template>
    <v-app>
        <subheading_f></subheading_f>
        <v-sheet
            class="mx-auto mt-12"
            elevation="0"
            color="grey lighten-5"
        >
            <changepassword_f></changepassword_f>
        </v-sheet>
        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f></latestbestsellingtopratedproducts_f>
        <footer_f></footer_f>

    </v-app>
</template>


<script>

    import subheading_f from './subheading.vue'
    import footer_f from './footer.vue'
    import changepassword_f from './changepassword.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'


    export default {
        components:{
            subheading_f, footer_f, changepassword_f, latestviewsproducts_f, latestbestsellingtopratedproducts_f
        },
    }
</script>
