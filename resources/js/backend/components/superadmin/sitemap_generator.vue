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
                        Generate Sitemap
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col cols="auto" sm="12" md="6">
                                <v-text-field name="baseUrl" label="Sitemap Base URL" v-model="sitemapBaseUrl" placeholder="https://perfumehousebd.com"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="auto" sm="12" md="6">
                                <linkprocessor :dataobject='{ name:"Generate Sitemap", processName: "Generating Sitemap...", link: getGenerateSitemap(), type: "GET", color: "success", icon : {name: "cloud_download"}, isAlertShow:true, downloadLink: "/sitemap.xml" }'></linkprocessor>
                            </v-col>
                        </v-row>
                    </v-card-text>

                </v-card>
            </v-content>
        </v-sheet>

        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >

            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Sitemaps
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
                                                        <v-text-field name="sitemap" label="Sitemap*" v-model="sitemap.sitemap"
                                                            :rules="sitemapRules"
                                                            :error-messages="sitemapError.sitemap"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="sitemapUrl" label="Sitemap URL*" v-model="sitemap.sitemapUrl"
                                                            :rules="sitemapRules"
                                                            :error-messages="sitemapError.sitemapUrl"
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
                        :items="sitemaps"
                        :search="search"
                        >


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.id)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.id)" v-on="on">delete</v-icon>
                                    </template>
                                    <span>Delete Record?</span>
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

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './../subheading.vue'
    import modal_alert from './../../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../../frontend/components/modals/alertMixins.vue'

    import linkprocessor from './../link/linkprocessor.vue'

    export default {
        mounted() {
            this.getSitemaps();
        },
        components:{
            subheading, modal_alert, linkprocessor
        },
        mixins: [modal_alert_mixin ],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'ID',
                        align: 'start',
                        // sortable: false,
                        value: 'id',
                    },
                    { text: 'Sitemap', value: 'sitemap' },
                    { text: 'Sitemap Url', value: 'sitemapUrl' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                sitemaps: [],

                // add dialog
                formTitle: 'Sitemap URL',
                sitemap: {
                    sitemap: '', sitemapUrl: ''
                },
                sitemapError: {
                    sitemap: '', sitemapUrl: ''
                },
                sitemapRules: [
                    v => !!v || 'Sitemap is required',
                ],
                sitemapUrlRules: [
                    v => !!v || 'Sitemap URL is required',
                ],

                sitemapBaseUrl: '',
            }
        },
        methods: {

            getSitemaps(){
                var _this = this;
                axios.get('/api/getSitemaps'+'?token='+this.token)
                .then(function (response) {
                    _this.sitemaps = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.sitemap= { sitemap: '', sitemapUrl: '' }
                this.sitemapError= { sitemap: '', sitemapUrl: '' }
            },

            adding(){
                console.log(this.sitemap)
                var _this=this
                axios.post('/api/addSitemap'+'?token='+this.token, this.sitemap)
                .then(function (response) {
                    _this.getSitemaps()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.sitemapError = { sitemap: error.response.data.sitemap, sitemapUrl: error.response.data.sitemapUrl };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteSitemap/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getSitemaps()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getSitemap/'+id+'?token='+this.token)
                .then(function (response) {
                    _this.sitemap = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editSitemap?token='+this.token, this.sitemap)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getSitemaps()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.sitemapError = { sitemap: error.response.data.sitemap, sitemapUrl: error.response.data.sitemapUrl };
                })
            },

            getGenerateSitemap(){
                return '/api/generateSitemap'+'?url='+this.sitemapBaseUrl+'&token='+this.token ;
            }

        },
    }
</script>
