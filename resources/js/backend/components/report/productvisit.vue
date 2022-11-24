<template>
    <v-app  id="inspire">

        <subheading></subheading>



        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >
            <v-content>
                <dailyproductvisitsreport ></dailyproductvisitsreport>
                <monthlyproductvisitsreport ></monthlyproductvisitsreport>
                <yearlyproductvisitsreport ></yearlyproductvisitsreport>
                <productvisits ></productvisits>
            </v-content>
        </v-sheet>


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'
    import dailyproductvisitsreport from './dailyproductvisitsreport.vue'
    import monthlyproductvisitsreport from './monthlyproductvisitsreport.vue'
    import yearlyproductvisitsreport from './yearlyproductvisitsreport.vue'
    import productvisits from './productvisits.vue'

    export default {
        components:{
            subheading, dailyproductvisitsreport, monthlyproductvisitsreport, yearlyproductvisitsreport , productvisits
        },
        mounted() {
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

