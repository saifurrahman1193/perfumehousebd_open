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
                        Carts
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row class="mb-2">
                            <v-col cols="12" sm="12" md="6">
                                <v-autocomplete
                                    label="Customer"
                                    :items="users"
                                    item-text="customer"
                                    item-value="id"
                                    dense
                                    id="userId"
                                    name="userId"
                                    clearable
                                    v-model="user_filter_userId"
                                    @change="getCarts()"
                                >
                                    <template v-slot:item="data" >
                                            <v-list-item-avatar>
                                                <img :src="data.item.photoPath" v-if="data.item.photoPath">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                                <v-list-item-subtitle>{{ string_to_normalbehaviour((data.item.phone||'') +', ' + (data.item.email||'') +', ' +(data.item.area || '') +', ')}}</v-list-item-subtitle>
                                            </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </v-col>

                            <v-spacer></v-spacer>
                            <v-text-field
                                class="mb-2"
                                v-model="search"
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-row>


                        <v-data-table
                        :headers="headers"
                        :items="carts"
                        :search="search"
                        class="gray-table"
                        :custom-filter="customFilter"
                        >
                            <template v-slot:[`item.customer`]="{ item }" >
                                <customerinfo :user="getUser(item.userId)"></customerinfo>
                            </template>

                            <template v-slot:[`item.cart`]="{ item }">
                                <cartstatus  :cart="item" @reloadData="getCarts()" ></cartstatus>


                                <v-rating
                                        v-model="item.customerRating"
                                        background-color="orange lighten-3"
                                        color="orange"
                                        medium
                                        dense
                                        readonly
                                    >
                                </v-rating>

                                <v-btn
                                    class="mx-0 mr-2"
                                    icon
                                    :href="'/api/printInvoice/'+item.cartId+'?token='+token"
                                    target="_blank"
                                    color="pink"
                                    x-large
                                >
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on">picture_as_pdf</v-icon>
                                        </template>
                                        <span>Print Invoice</span>
                                    </v-tooltip>
                                </v-btn>
                            </template>


                            <template v-slot:[`item.cartdetails`]="{ item }" >
                                <orderdetails_f :cart="getSelectedCart(item.cartId)" :cartdetails="getSelectedCartDetails(item.cartId)"  :deliveryChange="true" @reloadData="getCarts();getCartDetails();getTransactions()"/>
                            </template>



                            <template v-slot:[`item.transactions`]="{ item }" >
                                <carttransactions  :transactions="getCartTransactions(item.cartId)" :paymentmethods="paymentmethods" :cartId="item.cartId" :customerId="item.userId"  :cartProductsTotalSum="item.cartProductsTotalSum" @reloadTransactions="getTransactions()"/>
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

    import subheading from './subheading.vue'
    import orderdetails_f from './../../frontend/components/orderdetails.vue'
    import cartstatus from './../../frontend/components/cartstatus.vue'
    import carttransactions from './carttransactions.vue'
    import customerinfo from './customerinfo.vue'
    import algorithms from './../../mixins/algorithms.vue'

    export default {
        components:{
            subheading,   orderdetails_f, cartstatus, carttransactions, customerinfo
        },
        mixins: [algorithms],
        mounted() {
            this.getCarts();
            this.getCartDetails();
            this.getTransactions();
            this.getPaymentMethods();
            this.search = this.$route.query.cartId
            this.getUsers()

        },
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Cart Id',
                        align: 'start',
                        // sortable: false,
                        value: 'cartId',
                    },
                    { text: 'Customer', value: 'customer' , sortable: false,},
                    { text: 'Cart', value: 'cart' , sortable: false,},
                    { text: 'Cart Details', value: 'cartdetails' , sortable: false, width:570},
                    { text: 'Transactions', value: 'transactions' , sortable: false,},

                ],
                carts:[],
                cartdetails:[],
                transactions:[],
                paymentmethods:[],
                users:[],
                user_filter_userId: '',


            }
        },
        methods: {
            getUsers(){
                var _this = this;
                axios.get('/api/getUsersView'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users = response.data.data;
                    // console.log(_this.users)
                    _this.users = _this.arrayOfObjectSort(_this.users, 'name')
                    console.log(_this.users)
                })
                .catch(function (error) {
                })
            },
            getUser(userId){
                return this.users.filter(function(user){
                    return user.id==userId
                })[0]
            },
            getCarts(){
                var _this = this;
                if (this.user_filter_userId) {

                    axios.get('/api/getCartsWithUser/'+this.user_filter_userId+'?token='+this.token)
                    .then(function (response) {
                        _this.carts = response.data.data.reverse();
                    })
                    .catch(function (error) {
                    })
                } else {
                    axios.get('/api/getCarts?token='+this.token)
                    .then(function (response) {
                        _this.carts = response.data.data.reverse();
                    })
                    .catch(function (error) {
                    })
                }
            },

            getCartDetails(){
                var _this = this;
                axios.get('/api/getCartDetails?token='+this.token)
                .then(function (response) {
                    _this.cartdetails = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getSelectedCart(cartId){
                return this.carts.filter(function(cart){
                    return cartId==cart.cartId
                })[0]
            },

            getSelectedCartDetails(cartId){
                return this.cartdetails.filter(function(cartdetail){
                    return cartId==cartdetail.cartId
                })
            },

            getTransactions(){
                var _this = this;
                axios.get('/api/getTransactions?token='+this.token)
                .then(function (response) {
                    _this.transactions = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getCartTransactions(cartId){
                return this.transactions.filter(function(transaction){
                    return cartId==transaction.cartId
                })
            },

            getPaymentMethods(){
                var _this = this;
                axios.get('/api/getPaymentMethods?token='+this.token)
                .then(function (response) {
                    _this.paymentmethods = response.data.data;
                })
                .catch(function (error) {
                })
            },

            customFilter(value, search, item){

                // if(value && search && typeof value === 'string'){
                if(value && search ){

                    // console.log('called')
                    // console.log('value = ', value)
                    // console.log('search')
                    // console.log(search)
                    // console.log('item')
                    // console.log(item)
                    // console.log(item)

                    var totalSearchItemString = this.string_concat(' ',[item.cartId, item.name, item.phone ])

                    // console.log(totalSearchItemString)

                    return totalSearchItemString.toString().toLowerCase().indexOf(search) !== -1
                }
            },


        },

    }
</script>

