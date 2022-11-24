<template>


    <div>
        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >

            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Discount Customers
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row>


                            <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2 ml-5" v-on="on">Add a new {{ formTitle }}</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <span class="headline"> <span v-if="!editingId">Add a new </span><span v-if="editingId">Edit </span><strong>{{ formTitle }}</strong></span>
                                        <v-spacer></v-spacer>
                                    </v-card-title>

                                    <v-form @submit.prevent="crudConfirm()">
                                        <v-card-text>
                                            <v-container>
                                                <v-row>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Customer*"
                                                            :items="users"
                                                            item-text="name"
                                                            item-value="id"
                                                            dense
                                                            filled
                                                            v-model="discountcustomer.customerId"
                                                            clearable
                                                        >
                                                        </v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountCustomerPercent" label="Percent (%)*"
                                                            v-model="discountcustomer.discountCustomerPercent"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountCustomerAmount" label="Amount*"
                                                            v-model="discountcustomer.discountCustomerAmount"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-menu
                                                            v-model="discountCustomerExpireDateMenu"
                                                            transition="scale-transition"
                                                            offset-y
                                                            max-width="290px"
                                                            min-width="290px"
                                                            :close-on-content-click="false"
                                                        >
                                                            <template v-slot:activator="{ on }">
                                                                <v-text-field
                                                                    label="Expire Date*"
                                                                    append-icon="event"
                                                                    v-model="formattedDiscountCustomerExpireDate"
                                                                    v-on="on"
                                                                ></v-text-field>
                                                            </template>

                                                            <v-date-picker
                                                            v-model="discountcustomer.discountCustomerExpireDate"
                                                            no-title
                                                            dense
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn @click="discountCustomerExpireDateMenu=false" icon>Ok</v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea
                                                        v-model="discountcustomer.discountReason"
                                                        auto-grow  outlined  rows="4"
                                                        counter
                                                        clearable
                                                        pt-2
                                                        label="Discount Reason"

                                                        ></v-textarea>
                                                    </v-col>


                                                </v-row>

                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue darken-1" text @click="close()">Cancel</v-btn>
                                            <v-btn color="blue darken-1" text type="submit">Save</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-card>
                            </v-dialog>


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
                        :items="discountcustomers"
                        :search="search"
                        >
                            <template v-slot:[`item.customer`]="{ item }">
                                    <v-list-item > {{item.customerName}}</v-list-item>
                                    <v-list-item > {{item.customerEmail}}</v-list-item>
                            </template>

                            <template v-slot:[`item.discountCustomerPercent`]="{ item }">
                                    {{item.discountCustomerPercent}}<span v-if="item.discountCustomerPercent>0">%</span>
                            </template>

                            <template v-slot:[`item.discountCustomerExpireDate`]="{ item }">
                                    <v-list-item v-if="item.discountCustomerExpireDate"> {{formatDate(item.discountCustomerExpireDate)}}</v-list-item>
                            </template>

                            <template v-slot:[`item.product`]="{ item }">
                                    <v-list-item > {{item.productName}}</v-list-item>
                                    <v-list-item v-if="item.qty && item.uom"> {{item.qty+' '+item.uom}}</v-list-item>
                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.discountCustomerId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.discountCustomerId)" v-on="on">delete</v-icon>
                                    </template>
                                    <span>Delete Record ?</span>
                                </v-tooltip>

                            </template>

                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

        <modal_alert
            :s_alert="s_alert"
            s_msg="Successfully saved !"
            :e_alert="e_alert"
            e_msg="Something went wrong !"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed ?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"

        ></modal_alert>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>
    </div>


</template>


<script>
    import subheading from './subheading.vue'
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    // zoom
    import zoom_modal from './../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../frontend/components/zoom/zoomMixins.vue'

    // pic
    import picture_mixin from './../../mixins/picture.vue'

    export default {
        components:{
            subheading, modal_alert, zoom_modal
        },
        mounted() {
            this.getDiscountCustomers();
            this.getUsers();
        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Id',
                        align: 'start',
                        // sortable: false,
                        value: 'discountCustomerId',
                    },
                    { text: 'Customer', value: 'customer' },
                    { text: 'Percent(%)', value: 'discountCustomerPercent' },
                    { text: 'Amount', value: 'discountCustomerAmount' },
                    { text: 'Expire Date', value: 'discountCustomerExpireDate' },
                    { text: 'Reason', value: 'discountReason' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                discountcustomers: [],

                // add dialog
                formTitle: 'Discount Customer',
                discountcustomer: {
                    discountCustomerId:'',   discountCustomerPercent: '', discountCustomerAmount:'', discountCustomerExpireDate: '', discountReason: ''
                },
                discountcustomerError: {
                    discountCustomerId:'',   discountCustomerPercent: '', discountCustomerAmount:'', discountCustomerExpireDate: '', discountReason: ''
                },
                discountCustomerExpireDateMenu:false,
                users:[],


            }
        },
        methods: {

            getUsers(){
                var _this = this;
                axios.get('/api/getUsers'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getDiscountCustomers(){
                var _this = this;
                axios.get('/api/getDiscountCustomers')
                .then(function (response) {
                    _this.discountcustomers = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.discountcustomer= {discountCustomerId:'',   discountCustomerPercent: '', discountCustomerAmount:'', discountCustomerExpireDate: '', discountReason: '' }
                this.discountcustomerError= {discountCustomerId:'',   discountCustomerPercent: '', discountCustomerAmount:'', discountCustomerExpireDate: '', discountReason: '' }
            },

            adding(){
                var _this=this
                axios.post('/api/addDiscountCustomer'+'?token='+this.token, this.discountcustomer)
                .then(function (response) {
                    _this.getDiscountCustomers()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountcustomerError = {discountCustomerId: error.response.data.discountCustomerId, discountCustomerPercent: error.response.data.discountCustomerPercent, discountCustomerAmount: error.response.data.discountCustomerAmount, discountCustomerExpireDate: error.response.data.discountCustomerExpireDate, };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deleteDiscountCustomer/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getDiscountCustomers()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getDiscountCustomer/'+id)
                .then(function (response) {
                    _this.discountcustomer = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editDiscountCustomer?token='+this.token, this.discountcustomer)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getDiscountCustomers()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountcustomerError = {discountCustomerPercent: error.response.data.discountCustomerPercent, discountCustomerAmount: error.response.data.discountCustomerAmount, discountCustomerExpireDate: error.response.data.discountCustomerExpireDate, };
                })
            },

        },
        computed: {
            formattedDiscountCustomerExpireDate: {
                get(){
                    return this.formatDate(this.discountcustomer.discountCustomerExpireDate);
                },
                set(val){
                    this.discountcustomer.discountCustomerExpireDate = this.formatDate(val);
                }
            },
        },
        watch: {
            'discountcustomer.discountCustomerPercent': {
                handler: function (after, before) {
                    console.log('discountcustomer.discountCustomerPercent before= '+before)
                    console.log('discountcustomer.discountCustomerPercent after= '+after)
                    if(after > 0)
                    {
                        this.discountcustomer.discountCustomerAmount=''
                    }
                },
                deep: true
            },
            'discountcustomer.discountCustomerAmount': {
                handler: function (after, before) {
                    console.log('discountcustomer.discountCustomerAmount before= '+before)
                    console.log('discountcustomer.discountCustomerAmount after= '+after)
                    if(after > 0)
                    {
                        this.discountcustomer.discountCustomerPercent=''
                    }
                },
                deep: true
            }
        }
    }
</script>

