<template>
    <div >
        <v-card elevation="1" max-width="240" class="mx-auto" @click="dialog_crud=true">
            <v-list-item class="pa-0">
                <v-list-item-avatar >
                    <v-img src="/uploads/paymentmethods/bkash.svg" max-height="60" max-width="50"  aspect-ratio contain>
                        <template v-slot:placeholder>
                            <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center"
                            >
                                <v-progress-circular indeterminate color="#C9151B"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    bKash (TrxID method)
                </v-list-item-content>
            </v-list-item>
        </v-card>

        <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent style="z-index: 10000000000;">
            <v-card>

                <v-form @submit.prevent="crudConfirm()">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field  label="Account Number" v-model="transaction.accountNumber"  readonly
                                    >
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field  label="Option" v-model="transaction.option"  readonly
                                    >
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field name="transactionAmount" label="Transaction Amount" v-model="transaction.transactionAmount" prefix="৳"
                                        :rules="transactionAmountRules"
                                        :error-messages="transactionError.transactionAmount"
                                         type="number"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field name="accountType" label="Type" v-model="transaction.accountType" readonly
                                    ></v-text-field>
                                </v-col>




                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-divider v-if="loading"></v-divider>
                    <p v-if="loading" class="text-danger mt-1 red--text lighten-1 text-center">Processing... Please wait.</p>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="close()" :disabled="loading || !paymentmethod.paymentMethodId">Cancel</v-btn>
                        <v-btn color="success"  type="submit" :disabled="loading || !paymentmethod.paymentMethodId">Submit<v-icon right dark>send</v-icon></v-btn>

                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>

        <modal_alert
            :s_alert="s_alert"
            s_msg="Successfully saved!"
            :e_alert="e_alert"
            e_msg="Something went wrong!"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"

        >
        </modal_alert>

    </div>
</template>

<script>
    import modal_alert from './modals/alert.vue'
    import modal_alert_mixin from './modals/alertMixins.vue'

export default {
    components:{
        modal_alert
        },
    mixins: [modal_alert_mixin],
    mounted() {
        this.loading=false;
        this.getPaymentMethod()
    },
    props: ["cart"],
    data() {
        return {
            token:localStorage.getItem('token'),
            dialog_crud:false,
            paymentmethod:{},
            paymentMethodId: 2,
            transaction: {
                accountNumber: '',
                option: '',
                transactionAmount: '',
                cartId: this.cart.cartId,
                customerId: this.cart.userId,
                paymentMethodId: 2,
                transactionDate:this.getTodayDateYmd()
            },
            loading:true,
            transactionAmountRules: [
                v => !!v || 'Transaction amount is required',
            ],
            transactionError: {
                transactionAmount: ''
            },

        }
    },

    methods: {
        getPaymentMethod(){
            var _this = this;
            axios.get('/api/getPaymentMethod/'+this.paymentMethodId+'?token='+this.token)
            .then(function (response) {
                _this.paymentmethod = response.data.data;
                _this.transaction.accountNumber = _this.paymentmethod.accountNumber
                _this.transaction.option = _this.paymentmethod.option
                _this.transaction.accountType = _this.paymentmethod.accountType
            })
            .catch(function (error) {
            })
        },

        adding(){
            this.loading=true;
            var _this=this
            axios.post('/api/addTransaction?token='+this.token, this.transaction)
            .then(function (response) {
                _this.s_alert = true;
                _this.dialog_crud = false
                _this.clearForm()
                _this.addingConfirmed=''

                _this.loading=false;
                _this.$emit('getCurrentUserCart')
            })
            .catch(function (error) {
                _this.e_alert = true;
                _this.transactionError = {
                    transactionAmount: error.response.data.transactionAmount,
                 };

                _this.loading=false;
            })
        },
        clearForm(){
            this.transaction.transactionAmount= ''
            this.transactionError.transactionAmount= ''
        },


    },
    computed: {

    },
}
</script>

