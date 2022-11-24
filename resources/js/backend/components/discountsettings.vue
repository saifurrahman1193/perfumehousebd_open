<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <!-- <discountproductcustomer></discountproductcustomer>
        <discountcustomer></discountcustomer>
        <discountproduct></discountproduct> -->
        <discountall></discountall>


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import discountall from './discountall.vue'
    import discountproduct from './discountproduct.vue'
    import discountcustomer from './discountcustomer.vue'
    import discountproductcustomer from './discountproductcustomer.vue'

    export default {
        components:{
            subheading, discountall, discountproduct, discountcustomer, discountproductcustomer
        },
    }
</script>
