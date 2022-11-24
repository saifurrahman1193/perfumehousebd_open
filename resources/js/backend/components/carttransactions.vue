<template>
    <div>
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
                                    <v-text-field name="transactionAmount" label="Transaction Amount" v-model="transaction.transactionAmount" prefix="৳"
                                        :rules="transactionAmountRules"
                                        :error-messages="transactionError.transactionAmount"
                                         type="number"
                                    ></v-text-field>

                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-menu
                                        v-model="transactionDateMenu"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                        :close-on-content-click="false"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                label="Transaction Date"
                                                append-icon="event"
                                                v-model="formattedTransactionDate"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>

                                        <v-date-picker
                                        v-model="transaction.transactionDate"
                                        no-title
                                        dense
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn @click="transactionDateMenu=false" icon>Ok</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col  cols="12" sm="12" md="12">
                                    <v-autocomplete
                                        label="Payment Method"
                                        :items="paymentmethods"
                                        item-text="paymentMethod"
                                        item-value="paymentMethodId"
                                        dense
                                        filled
                                        v-model="transaction.paymentMethodId"
                                        clearable
                                    >
                                    </v-autocomplete>
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





        <v-simple-table  dense>
            <template v-slot:default v-if="(transactions || []).length > 0">
                <thead >
                    <tr>
                        <th class="text-left">Transaction Id</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Payment Method</th>
                        <th class="text-left">Entry By</th>
                        <th class="text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in transactions" :key="index">
                        <td>{{ row.transactionId }}</td>
                        <td>{{ row.transactionAmount }}</td>
                        <td>{{ row.paymentMethod }}</td>
                        <td>{{ row.entryByUserName }}</td>
                        <td>{{ formatDate(row.transactionDate) }}</td>
                    </tr>
                    <tr>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="text-success">
                        <th  class="text-right ">Total Paid</th>
                        <th style="font-size:17px;">{{totalPaid}}</th>
                    </tr>
                    <tr class="text-danger">
                        <th   class="text-right">Total Due</th>
                        <th style="font-size:17px;">{{totalDue}}</th>
                    </tr>
                </tfoot>
            </template>
        </v-simple-table>

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

    </div>
</template>


<script>
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    export default {
        components:{
            modal_alert
        },
        mounted() {
        },
        mixins: [modal_alert_mixin,],
        data() {
            return {
                token: localStorage.getItem('token'),
                formTitle: 'Transaction',
                transaction: {
                    transactionAmount: ''
                },
                transactionError: {
                    transactionAmount: ''
                },
                transactionAmountRules: [
                    v => !!v || 'Transaction amount is required',
                ],
                transactionDateMenu:false,

            }
        },
        props: ["transactions", "paymentmethods", "cartId", "customerId", "cartProductsTotalSum"],
        methods: {

            clearForm(){
                this.transaction= { transactionAmount: ''}
                this.transactionError= { transactionAmount: ''}
            },

            adding(){
                var _this=this
                this.transaction.cartId = this.cartId
                this.transaction.customerId = this.customerId
                axios.post('/api/addTransaction?token='+this.token, this.transaction)
                .then(function (response) {
                    _this.$emit('reloadTransactions')
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.transactionError = { transactionAmount: error.response.data.transactionAmount };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deleteTransaction/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    // _this.getTransactions()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getTransaction/'+id)
                .then(function (response) {
                    _this.transaction = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editTransaction?token='+this.token, this.transaction)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    // _this.getTransactions()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.transactionError = { transactionAmount: error.response.data.transactionAmount };
                })
            },



        },
        computed: {
            formattedTransactionDate: {
                get(){
                    return this.formatDate(this.transaction.transactionDate);
                },
                set(val){
                    this.transaction.transactionDate = this.formatDate(val);
                }
            },
            totalPaid: function(){
                var amountTotal = 0

                this.transactions.forEach(element => {
                    amountTotal+=parseFloat(element.transactionAmount)
                });

                return amountTotal
            },
            totalDue: function(){
                return this.cartProductsTotalSum-this.totalPaid
            },
        }
    }
</script>

