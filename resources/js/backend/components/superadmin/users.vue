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
                        Users
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
                                                        <v-text-field name="name" label="Name" v-model="user.name"
                                                            :rules="nameRules"
                                                            :error-messages="userError.name"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="email" label="Email" v-model="user.email"
                                                            :rules="emailRules"
                                                            :error-messages="userError.email"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="email" label="Password" v-model="user.password"
                                                            :rules="passwordRules"
                                                            :error-messages="userError.password"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-autocomplete
                                                        label="Roles"
                                                        :items="roles"
                                                        item-text="roleName"
                                                        item-value="roleId"
                                                        dense
                                                        filled
                                                        id="roleIds"
                                                        name="roleIds"
                                                        v-model="user.roleIds"
                                                        multiple
                                                        chips
                                                    ></v-autocomplete>


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
                        :items="users"
                        :search="search"
                        >
                            <template v-slot:[`item.roles`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1"
                                        v-for="(users_with_roles_item, index) in getUserWithRoles(item.id)"  :key="index">
                                        {{users_with_roles_item.roleName}}
                                    </v-list-item-title>
                                </v-list>
                            </template>


                            <template v-slot:[`item.photoPath`]="{ item }">
                                <v-img
                                    :src="item.photoPath || '/uploads/no_image.png'"
                                    lazy-src="/uploads/loader.gif"
                                    max-height="70"
                                    max-width="70"
                                    aspect-ratio contain
                                    @click="imageZoom(item.photoPath)"
                                    class="image-hover-cursor-change"
                                ></v-img>
                            </template>



                            <template v-slot:[`item.user`]="{ item }">
                                <v-simple-table  dense >
                                    <template v-slot:default >

                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td>{{ item.name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ item.email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{ item.phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Area</th>
                                                <td>{{ item.area }}</td>
                                            </tr>
                                            <tr>
                                                <th>Registry Date</th>
                                                <td>{{ datetimeprocessing(item.created_at) }}</td>
                                            </tr>

                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </template>

                            <template v-slot:[`item.action`]="{ item }">



                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.id)"  v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                            <v-icon @click="deleteConfirm(item.id)" v-on="on">delete</v-icon>
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
    import modal_alert from './../../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../../frontend/components/modals/alertMixins.vue'

    // zoom
    import zoom_modal from './../../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../../frontend/components/zoom/zoomMixins.vue'

    export default {
        components:{
            modal_alert, zoom_modal
        },
        mounted() {
            this.getRoles();
            this.getUsers();
            this.getUsersWithRoles();

        },
        mixins: [modal_alert_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'User Id',
                        align: 'start',
                        // sortable: false,
                        value: 'id',
                    },

                    { text: 'Picture', value: 'photoPath' },
                    { text: 'User', value: 'user' },
                    { text: 'Roles', value: 'roles' , sortable: false },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                users: [],
                users_with_roles:[],
                roles: [],

                formTitle: 'User',
                user: {
                    name: '',
                    email: '',
                    password: '',
                    roleIds:[]
                },
                userError: {
                    name: '',
                    email: '',
                    password: '',
                },
                nameRules: [
                    v => !!v || 'Name is required',
                ],
                emailRules: [
                    v => !!v || 'Email is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                    v => v.length >= 6 || "Password must be at least 6 characters long",
                ],
            }
        },
        methods: {
            getRoles(){
                var _this = this;
                axios.get('/api/getRoles'+'?token='+ _this.token)
                .then(function (response) {
                    _this.roles = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getUsers(){
                var _this = this;
                axios.get('/api/getUsersView'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.user= { name: '', email: '',  password: '' }
                this.userError= { name: '', email: '', password: '' }
            },

            adding(){
                var _this=this
                axios.post('/api/addUser/'+'?token='+_this.token, _this.user)
                .then(function (response) {
                    _this.getUsers()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''

                    _this.getUsersWithRoles()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.userError =  { name: error.response.data.name, email: error.response.data.email, password: error.response.data.password };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteUser/'+_this.deletingId+'?token='+_this.token)
                .then(function (response) {
                    _this.getUsers()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getUser/'+id+'?token='+_this.token)
                .then(function (response) {
                    _this.user = response.data.data;
                    _this.user.roleIds = _this.getUserRoles(id)
                })
                .catch(function (error) {
                })

            },

            editing(){
                var _this = this;
                axios.post('/api/editUser?token='+_this.token, _this.user)
               .then(function (response) {
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.getUsers()
                    _this.clearForm()
                    _this.editingId=''

                    _this.getUsersWithRoles()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.userError = { email: error.response.data.email };
                })
            },

            getUsersWithRoles(){
                var _this = this;
                axios.get('/api/getUsersWithRoles'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users_with_roles = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getUserWithRoles(userId){
                return this.users_with_roles.filter(function(role){
                    return role.userId == userId
                });
            },
            getUserRoles(userId){
                return this.users_with_roles.filter(function(role){
                    return role.userId == userId
                })
                .map(function(role) {
                    return role.roleId;
                });
            },



        },
        computed: {

        }
    }
</script>
