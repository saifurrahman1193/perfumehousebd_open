<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <paymentmethods/>


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import paymentmethods from './paymentmethods.vue'

    export default {
        components:{
            subheading, paymentmethods,
        },
    }
</script>
