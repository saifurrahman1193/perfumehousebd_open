<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >

            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Wishlist Report
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
                        :items="wishlistall"
                        :search="search"
                        >
                            <template v-slot:[`item.product`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1" >{{item.productName}}</v-list-item-title>
                                    <v-list-item-title class="pl-1" v-if="item.qty>0" >{{item.qty+' '+ item.uom}}</v-list-item-title>
                                </v-list>
                            </template>

                            <template v-slot:[`item.customer`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1" >{{item.name}}</v-list-item-title>
                                    <v-list-item-title class="pl-1" >{{item.email}}</v-list-item-title>
                                    <v-list-item-title class="pl-1" v-if="item.phone" >{{item.phone}}</v-list-item-title>
                                </v-list>
                            </template>



                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

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
            this.getWishlistall()
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
                        value: 'wishlistallId',
                    },
                    { text: 'Customer', value: 'customer' },
                    { text: 'product', value: 'product' },
                    { text: 'Date-Time', value: 'datetime' },
                ],
                wishlistall:[],
            }
        },
        methods: {

            getWishlistall(){
                var _this = this;
                axios.get('/api/getWishlistall?token='+ this.token)
                .then(function (response) {
                    _this.wishlistall = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },
        },
        computed: {

        }
    }
</script>

