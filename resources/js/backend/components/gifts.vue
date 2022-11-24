<template>
    <v-app  id="inspire">

        <subheading></subheading>
        <gifttypes></gifttypes>

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import gifttypes from './gifttypes.vue'

    export default {
        components:{
            subheading, gifttypes,
        },
    }
</script>

