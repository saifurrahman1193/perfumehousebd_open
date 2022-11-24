<template>
    <v-app>
        <subheading_f></subheading_f>
        <profile_f></profile_f>
        <v-spacer></v-spacer>

    </v-app>
</template>


<script>

    import subheading_f from './subheading.vue'
    import profile_f from './../../frontend/components/profile.vue'

    export default {
        components:{
            subheading_f,  profile_f
        },

    }
</script>
