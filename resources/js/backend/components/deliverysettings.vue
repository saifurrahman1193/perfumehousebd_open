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
                            Update  Delivery Settings
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="deliverySettingPrice" label="Inside Dhaka Metro City Delivery Charge*"
                                        v-model="deliverysettings.deliverySettingPrice"
                                        type="number"
                                        :rules="deliverySettingPriceRules"
                                        :error-messages="deliverysettingsError.deliverySettingPrice"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="deliverySettingPrice" label="Outside Dhaka Metro City Delivery Charge*"
                                        v-model="deliverysettings.outsideDahakeDeliveryPrice"
                                        type="number"
                                        :rules="outsideDahakeDeliveryPriceRules"
                                        :error-messages="deliverysettingsError.outsideDahakeDeliveryPrice"
                                    ></v-text-field>
                                </v-col>


                            </v-row>



                        </v-card-text>
                        <v-card-actions >
                            <v-spacer></v-spacer>
                            <v-btn color="error" text  @click="close()">Clear</v-btn>
                            <v-btn color="success"  type="submit">Save</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-form>
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


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'



    export default {
        components:{
            subheading, modal_alert,
        },
        mounted() {
            this.getDeliverySetting()

        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                deliverysettings:{
                    deliverySettingPrice:'', outsideDahakeDeliveryPrice:''
                },
                deliverysettingsError:{
                    deliverySettingPrice:'', outsideDahakeDeliveryPrice:''
                },
                editingId:1,
                deliverySettingPriceRules: [
                    v => !!v || 'Insaide Dhaka Metro City Delivery charge is required',
                ],
                outsideDahakeDeliveryPriceRules: [
                    v => !!v || 'Outside Dhaka Metro City Delivery charge is required',
                ],

            }
        },
        methods: {

            getDeliverySetting(){
                var _this = this;
                axios.get('/api/getDeliverySetting')
                .then(function (response) {
                    _this.deliverysettings = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this=this
                axios.post('/api/editDeliverySetting/'+'?token='+this.token, this.deliverysettings)
                .then(function (response) {
                    _this.getDeliverySetting()
                    _this.s_alert = true;
                    _this.clearForm()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.deliverysettingsError = { deliverySettingPrice: error.response.data.deliverySettingPrice };
                })
            },

            clearForm(){
                this.deliverysettings= { deliverySettingPrice:'' }
                this.deliverysettingsError= { deliverySettingPrice:'' }
                this.editingId=1
            },



        },
        computed: {

        },

    }
</script>

