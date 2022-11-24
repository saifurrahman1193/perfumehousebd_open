<template>
    <v-app>
        <subheading_f></subheading_f>
            <v-sheet
                class="mx-auto  mt-12"
                elevation="0"
                 color="grey lighten-5"
            >
                <v-row class="mt-10 hidden-sm-and-down"></v-row>

                <v-layout row wrap align-center class="mt-2">
                    <v-flex>
                        <v-card :width="isXS?320:500" class="mx-auto mb-2" floating>
                            <v-card-title>
                                <h1 class="display-1 mx-auto font-weight-light">Sign Up</h1>
                            </v-card-title>

                            <v-card-text>
                                <v-form  @submit.prevent="registrationPost()" >
                                    <v-text-field
                                        label="Name*"
                                        name="name"
                                        id="name"
                                        v-model="registrationDetails.name"
                                        type="text"
                                        :rules="nameRules"
                                        :error-messages="registrationDetailsError.name"

                                    ></v-text-field>
                                    <v-text-field
                                        label="Phone*"
                                        name="phone"
                                        id="phone"
                                        v-model="registrationDetails.phone"
                                        type="text"
                                        :rules="phoneRules"
                                        :error-messages="registrationDetailsError.phone"
                                    ></v-text-field>


                                    <!-- <v-select
                                        label="Area"
                                        :items="areas"
                                        item-text="area"
                                        item-value="areaId"
                                        autocomplete
                                        dense
                                        v-model="registrationDetails.areaId"
                                        :rules="areaRules"
                                        :error-messages="registrationDetailsError.areaId"
                                        :searchable="true"
                                    /> -->

                                    <!-- <v-autocomplete
                                        label="Area"
                                        v-model="registrationDetails.areaId"
                                        :items="areas"
                                        item-text="area"
                                        item-value="areaId"
                                        dense
                                        filled
                                        :rules="areaRules"
                                        :error-messages="registrationDetailsError.areaId"
                                        id="areaId"
                                        name="areaId"
                                    ></v-autocomplete> -->


                                    <v-text-field
                                        label="Email*"
                                        name="email"
                                        id="email"
                                        v-model="registrationDetails.email"
                                        :rules="emailRules"
                                        :error-messages="registrationDetailsError.email"
                                    ></v-text-field>

                                    <v-text-field
                                        label="Password*"
                                        name="password"
                                        id="password"
                                        v-model="registrationDetails.password"
                                        :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                        :type="showPassword ? 'text' : 'password'"
                                        @click:append="showPassword= !showPassword"
                                        :rules="passwordRules"
                                        :error-messages="registrationDetailsError.password"

                                    ></v-text-field>

                                    <v-divider></v-divider>

                                    <p v-if="registrationDetailsValid.valid==true" class="text-success mt-1 green--text lighten-1 text-center">{{ registrationDetailsValid.validMessage }}</p>

                                    <v-card-actions>
                                        <!-- <v-btn color="info" text  @click="gotorouterview('/login')">
                                            <v-icon>verified_user</v-icon>
                                            Sign In
                                        </v-btn> -->
                                        <v-btn  text  @click="gotorouterview('/login')" small>
                                            Sign In
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn color="success" type="submit" text @click="loading=true">
                                            <v-icon>person_add</v-icon>
                                            Sign Up
                                        </v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>

                <v-dialog v-model="loading" fullscreen >
                    <v-container fluid fill-height style="background-color: rgba(255, 255, 255, 0.5);">
                    <v-layout justify-center align-center>
                        <v-progress-circular
                        indeterminate
                        color="primary">
                        </v-progress-circular>
                    </v-layout>
                    </v-container>
                </v-dialog>

        </v-sheet>
        <v-spacer></v-spacer>

        <!-- <latestbestsellingtopratedproducts_f></latestbestsellingtopratedproducts_f> -->
        <footer_f></footer_f>
    </v-app>
</template>


<script>
    import subheading_f from './subheading.vue'
    import footer_f from './footer.vue'
    // import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import responsive from '../../mixins/responsive.vue'

    export default {
        components:{
            subheading_f, footer_f,
        },
        mixins: [responsive],
        mounted() {
            this.topFunction()
            console.log('Component mounted.');
            console.log(this.$store.getters.welcome)
            this.loading=false;

            // this.getAreas();
        },
        data() {
            return {
                showPassword:false,
                registrationDetails : {  email: '', password: '', name:'', phone:''},
                registrationDetailsError : { error:false, email: '' , password:'', name:'', phone:''},
                registrationDetailsValid : { valid:false, validMessage: '' },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                ],
                phoneRules: [
                    v => !!v || 'Phone is required',
                ],
                nameRules: [
                    v => !!v || 'Name is required',
                ],
                // areaRules: [
                //     v => !!v || 'Area is required',
                // ],
                loading:true,
                // areas: [],
                searchInput: ""
            }
        },
        methods: {
            // getAreas(){
            //     var _this = this;
            //     axios.get('/api/getAreas')
            //     .then(function (response) {
            //         _this.areas = response.data.data;
            //         console.log(response.data.data)
            //     })
            //     .catch(function (error) {
            //     })
            // },
            registrationPost(){

                    var _this = this;
                    console.log(this.registrationDetails.email)
                    // console.log(this.registrationDetails.areaId)
                    console.log(this.registrationDetails.phone)
                    axios.post('/api/auth/register',  this.registrationDetails)
                    .then(function (response) {
                        console.log('success !');
                        console.log(response.data.access_token);
                        // window.location.href = '/home/home?token='+response.data.access_token;
                        let token = response.data.access_token;
                        localStorage.setItem('token', token);
                        // _this.$router.go(-1);
                        window.location.href = '/';
                        _this.loading=false;
                        _this.registrationDetailsError = { error:false, email: "" , password:'', name:'', phone:''};
                        _this.registrationDetailsValid = { valid:true, validMessage: 'Successful ! Please wait..' }
                    })
                    .catch(function (error) {
                        // console.log('failure !');
                        // console.log(error.response);
                        // console.log(error.response.data.error);
                        // _this.registrationDetailsError = { error:true, email: error.response.data.error };
                        _this.registrationDetailsError = { error:true, email: error.response.data.email, password: error.response.data.password, name: error.response.data.name, phone: error.response.data.phone };
                        _this.loading=false;

                    })
            },
        },
        metaInfo: {
            titleTemplate: '%s | Register'
        }
    }
</script>
