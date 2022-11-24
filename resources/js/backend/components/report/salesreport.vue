<template>
    <v-app >

        <subheading></subheading>

        <v-sheet
                class=" ma-2"
                elevation="0"
                 color="grey lighten-5"

            >

            <v-content>
                <v-row >

                    <dailysalesreport/>
                    <monthlysalesreport/>
                    <yearlysalesreport/>


                </v-row>
            </v-content>




        </v-sheet>

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>

    import subheading from '../subheading.vue'
    import dailysalesreport from './dailysalesreport.vue'
    import monthlysalesreport from './monthlysalesreport.vue'
    import yearlysalesreport from './yearlysalesreport.vue'


    export default {
        components:{
            subheading, monthlysalesreport, yearlysalesreport, dailysalesreport
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
