<template>
    <v-sheet class="pa-6 ">
        Prop testing component  
        <v-alert type="primary" :value="true"  >
            {{propsmessage.msg}}
        </v-alert>
        
    </v-sheet>
</template>

<script>
export default {
    data() {
        return {
            
        }
    },
    props: {
        propsmessage : {
            type:Object,
            
        }
    }
}
</script>