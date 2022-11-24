<template>
    <v-app  id="inspire">

        <subheading></subheading>


        <v-sheet
            class=" ma-2 mt-8"
            elevation="0"
            color="grey lighten-5"
        >

            <chat_component :contacts="contacts"> </chat_component>

        </v-sheet>

        <v-spacer></v-spacer>
        <footer_f></footer_f>


    </v-app>
</template>


<script>

    import subheading from './subheading.vue'
    import footer_f from './footer.vue'
    import chat_component from './../../backend/components/chat/chat_component.vue'

    export default {
        components:{
            subheading, footer_f, chat_component
        },
        mounted() {
            this.topFunction()
            this.getContacts();
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                contacts:[],
            }
        },
        methods: {
            getContacts(){
                var _this = this;
                axios.get('/api/getAdminContacts'+'?token='+ _this.token)
                .then(function (response) {
                    _this.contacts = response.data.data;
                })
                .catch(function (error) {
                })
            },
        },
        metaInfo: {
            titleTemplate: '%s Chat'
        }

    }
</script>

