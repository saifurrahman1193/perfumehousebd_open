<template>
    <v-app  id="inspire">

        <subheading></subheading>



        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'
    export default {
        components:{
            subheading,
        },
        mounted() {
        },
        data() {
            return {
                token: localStorage.getItem('token'),
            }
        },
        methods: {



        },
        computed: {

        }
    }
</script>

