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
                        Discount Points
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col cols="auto" sm="12" md="6" class="d-flex">
                                <info text="1 point = ? Amount" />
                                <v-text-field name="amount" label="Amount*" type="number" v-model="discountpoint.amount" placeholder="1 point with what Amount" ></v-text-field>
                            </v-col>

                            <v-col cols="auto" sm="12" md="6">
                                <v-btn color="success"  :disabled="!isFormValid" @click="crudConfirm">Save</v-btn>
                            </v-col>
                        </v-row>
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


    </div>


</template>


<script>
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'
    import info from './../../components/Information.vue'


    export default {
        components:{
            modal_alert, info
        },
        mounted() {
            this.getDiscountPoint()

        },
        mixins: [modal_alert_mixin, ],
        data() {
            return {
                token: localStorage.getItem('token'),
                discountpoint: {
                    amount: 0
                },
                discountpointError: {
                    amount: 0
                },

            }

        },
        methods: {

            getDiscountPoint(){
                var _this = this;
                axios.get('/api/getDiscountPoint')
                .then(function (response) {
                    _this.discountpoint = response.data.data;

                    _this.editingId = _this.discountpoint.discountPointId
                })
                .catch(function (error) {
                })
            },

            editing(){

                var _this = this;
                axios.post('/api/discountPointUpdate?token='+this.token, this.discountpoint)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountpointError = {
                        amount: error.response.data.amount,
                    };
                })
            },


        },
        computed: {
            isFormValid: function(){
                return ((this.discountpoint||{}).amount || 0)>0;
            }
        },
        watch: {

        }
    }
</script>
