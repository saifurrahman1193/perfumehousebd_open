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
                        Discount Badges
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
                                                        <v-text-field name="discountBadge" label="Discount Badge*" v-model="discountbadge.discountBadge"
                                                            :rules="discountBadgeRules"
                                                            :error-messages="discountbadgeError.discountBadge"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="reqPointsToAchieve" label="Points required to achieve*"
                                                            v-model="discountbadge.reqPointsToAchieve"
                                                            type="number"
                                                            :rules="reqPointsToAchieveRules"
                                                            :error-messages="discountbadgeError.reqPointsToAchieve"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discBadgeDiscountPercent" label="Discount (%)*"
                                                            v-model="discountbadge.discBadgeDiscountPercent"
                                                            type="number"
                                                            :rules="discBadgeDiscountPercentRules"
                                                            :error-messages="discountbadgeError.discBadgeDiscountPercent"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountUpToAmount" label="Discount Up To Amount*"
                                                            v-model="discountbadge.discountUpToAmount"
                                                            type="number"
                                                            :rules="discountUpToAmountRules"
                                                            :error-messages="discountbadgeError.discountUpToAmount"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="color" label="Badge Color*"
                                                            v-model="discountbadge.color"
                                                            :rules="colorRules"
                                                            :error-messages="discountbadgeError.color"
                                                        ></v-text-field>
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
                        :items="discountbadges"
                        :search="search"
                        >


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.discountBadgeId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top >
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.discountBadgeId)" v-on="on">delete</v-icon>
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

    </div>


</template>


<script>
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    export default {
        components:{
            modal_alert,
        },
        mounted() {
            this.getDiscountBadges();


        },
        mixins: [modal_alert_mixin, ],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Discount Badge Id',
                        align: 'start',
                        // sortable: false,
                        value: 'discountBadgeId',
                    },
                    { text: 'Discount Badge', value: 'discountBadge' },
                    { text: 'Points required to achieve', value: 'reqPointsToAchieve' },
                    { text: 'Discount %', value: 'discBadgeDiscountPercent' },
                    { text: 'Discount Up To Amount', value: 'discountUpToAmount' },
                    { text: 'Badge Color', value: 'color' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                discountbadges: [],

                // add dialog
                formTitle: 'Discount Badge',
                discountbadge: {
                    discountBadge: '', reqPointsToAchieve: '', discBadgeDiscountPercent: '', color: '', discountUpToAmount: ''
                },
                discountbadgeError: {
                    discountBadge: '', reqPointsToAchieve: '', discBadgeDiscountPercent: '', color: '', discountUpToAmount: ''
                },
                discountBadgeRules: [
                    v => !!v || 'Discount Badge is required',
                ],
                reqPointsToAchieveRules: [
                    v => !!v || 'Points required to achieve is required',
                ],
                discBadgeDiscountPercentRules: [
                    v => !!v || 'Discount % is required',
                ],
                discountUpToAmountRules: [
                    v => !!v || 'Discount Up To Amount is required',
                ],
                colorRules: [
                    v => !!v || 'Badge color is required',
                ],
            }

        },
        methods: {

            getDiscountBadges(){
                var _this = this;
                axios.get('/api/getDiscountBadges')
                .then(function (response) {
                    _this.discountbadges = response.data.data;
                })
                .catch(function (error) {
                })
            },


            clearForm(){
                this.discountbadge= {
                    discountBadge: '', reqPointsToAchieve: '', discBadgeDiscountPercent: '', color: '', discountUpToAmount: ''
                };
                this.discountbadgeError= {
                    discountBadge: '', reqPointsToAchieve: '', discBadgeDiscountPercent: '', color: '', discountUpToAmount: ''
                };
            },

            adding(){
                console.log(this.discountbadge)
                var _this=this
                axios.post('/api/addDiscountBadge'+'?token='+this.token, this.discountbadge)
                .then(function (response) {
                    _this.getDiscountBadges()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountbadgeError = {
                        discountBadge: error.response.data.discountBadge,
                        reqPointsToAchieve: error.response.data.reqPointsToAchieve,
                        discBadgeDiscountPercent: error.response.data.discBadgeDiscountPercent,
                        color: error.response.data.color
                    };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteDiscountBadge/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getDiscountBadges()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getDiscountBadge/'+id)
                .then(function (response) {
                    _this.discountbadge = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editDiscountBadge?token='+this.token, this.discountbadge)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getDiscountBadges()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.uomError = {
                        discountBadge: error.response.data.discountBadge, reqPointsToAchieve: error.response.data.reqPointsToAchieve, discBadgeDiscountPercent: error.response.data.discBadgeDiscountPercent
                     };
                })
            },


        },
        computed: {

        }
    }
</script>
