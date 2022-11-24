<template>

    <v-card elevation="2" >
        <v-card-title primary-title >
            <v-spacer></v-spacer>
            Product Visit Report
            <v-spacer></v-spacer>
        </v-card-title>

        <v-card-text>

            <v-row>

                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-row>

            <v-data-table
            :headers="headers"
            :items="productvisits"
            :search="search"
            >


                <template v-slot:[`item.product`]="{ item }">
                    <v-list dense>
                        <v-list-item-title class="pl-1" >{{item.productName}}</v-list-item-title>
                        <v-list-item-title class="pl-1" v-if="item.qty>0" >{{item.qty+' '+ item.uom}}</v-list-item-title>
                    </v-list>
                </template>

                <template v-slot:[`item.customer`]="{ item }">
                    <v-list dense v-if="item.userId">
                        <v-list-item-title class="pl-1" >{{item.name}}</v-list-item-title>
                        <v-list-item-title class="pl-1" >{{item.email}}</v-list-item-title>
                        <v-list-item-title class="pl-1" v-if="item.phone" >{{item.phone}}</v-list-item-title>
                    </v-list>
                    <v-list dense v-else>
                        <v-list-item-title class="pl-1" >Guest</v-list-item-title>
                    </v-list>
                </template>



            </v-data-table>

        </v-card-text>
    </v-card>

</template>


<script>

    export default {
        components:{
        },
        mounted() {
            this.getProductVisits()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Id',
                        align: 'start',
                        // sortable: false,
                        value: 'productvisitId',
                    },
                    { text: 'Customer', value: 'customer' },
                    { text: 'product', value: 'product' },
                    { text: 'Date-Time', value: 'datetime' },
                ],
                productvisits:[],
            }
        },
        methods: {

            getProductVisits(){
                var _this = this;
                axios.get('/api/getProductVisits?token='+ this.token)
                .then(function (response) {
                    _this.productvisits = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },

        },
        computed: {

        }
    }
</script>
