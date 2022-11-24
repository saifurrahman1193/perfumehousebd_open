<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <users/>

        <roles/>

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './../subheading.vue'
    import users from './users.vue'
    import roles from './roles.vue'

    export default {
        components:{
            subheading, users, roles
        },
    }
</script>
