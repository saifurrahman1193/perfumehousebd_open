<template>
    <v-tooltip top>
        <template v-slot:activator="{ on}">
            <v-icon v-on="on" class="pointer" color="primary">error_outline</v-icon>
        </template>
        <span>{{text}}</span>
    </v-tooltip>
</template>


<script>
    export default {
        props: ["text"],
    }
</script>

// in immediate parent div please add this class class="d-flex"
