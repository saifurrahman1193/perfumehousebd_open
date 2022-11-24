<template>
    <v-app>
        <subheading_f></subheading_f>
        <profile_f></profile_f>
        <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f></latestbestsellingtopratedproducts_f>
        <footer_f></footer_f>
    </v-app>
</template>


<script>

    import subheading_f from './subheading.vue'
    import profile_f from './profile.vue'
    import footer_f from './footer.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'


    export default {
        components:{
            subheading_f, footer_f, profile_f, latestviewsproducts_f, latestbestsellingtopratedproducts_f
        },

    }
</script>
