<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <discountpoints></discountpoints>
        <discountbadgescrud></discountbadgescrud>



        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import discountpoints from './discountpoints.vue'
    import discountbadgescrud from './discountbadgescrud.vue'

    export default {

        components:{
            subheading, discountpoints, discountbadgescrud,
        },
    }
</script>
