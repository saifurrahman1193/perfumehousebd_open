<template>
    <v-app>
        <subheading_f> </subheading_f>

        <v-sheet
                class="mx-auto mt-12"
                elevation="0"
                 color="grey lighten-5"
            >
            <v-row class="mt-10 hidden-sm-and-down"></v-row>

            <v-layout row wrap align-center>
                <v-flex>
                    <v-card :width="isXS?320:500"  class="mx-auto mt-4 mb-2" floating>
                        <v-card-title>
                            <h1 class="display-1 mx-auto font-weight-light">Sign In</h1>
                        </v-card-title>

                        <v-card-text>
                            <v-form  @submit.prevent="loginPost()" >
                                <v-text-field
                                    name="email"
                                    label="Email"
                                    id="email"
                                    v-model="loginDetails.email"
                                    prepend-icon="person"
                                    :rules="emailRules"
                                    :error-messages="loginDetailsError.email"
                                ></v-text-field>
                                <v-text-field
                                    name="password"
                                    label="Password"
                                    id="password"
                                    v-model="loginDetails.password"
                                    prepend-icon="lock"
                                    :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                    :type="showPassword ? 'text' : 'password'"
                                    @click:append="showPassword= !showPassword"
                                    :rules="passwordRules"
                                    :error-messages="loginDetailsError.password"

                                ></v-text-field>
                                <p class="text-right text-decoration-underline" style="cursor: pointer;" @click="gotorouterview('/resetpassword')">Reset password?</p>

                                <v-divider></v-divider>
                                <p v-if="loginDetailsError.error" class="text-danger mt-1 red--text lighten-1 text-center">{{ loginDetailsError.loginError }}</p>
                                <p v-if="loginDetailsValid.valid" class="text-success mt-1 green--text lighten-1 text-center">{{ loginDetailsValid.validMessage }}</p>

                                <v-card-actions>
                                    <!-- <v-btn color="info" text @click="gotorouterview('/register')">
                                        <v-icon>person_add</v-icon>
                                        Sign Up
                                    </v-btn> -->

                                    <v-btn  depressed  text @click="gotorouterview('/register')" small>
                                        Sign Up
                                    </v-btn>

                                    <v-spacer></v-spacer>
                                    <v-btn color="success" type="submit" text @click="loading=true">
                                        <v-icon>verified_user</v-icon>
                                        Sign In
                                    </v-btn>
                                </v-card-actions>

                                <v-divider></v-divider>
                                    <v-row class="mb-2">
                                        <v-btn  dark  text @click="gotorouterviewwithload('/login/google')" block  class="text-capitalize " style="background: #4F85EC !important;" >
                                            <v-row>
                                                <v-col cols="2">
                                                    <v-img src="/uploads/google.jpg" max-width="25" max-height="25"></v-img>
                                                </v-col>
                                                <v-col  style="margin-top: 4px !important;">Continue with Google</v-col>
                                            </v-row>
                                        </v-btn>
                                    </v-row>

                                    <v-row>
                                        <v-btn  dark  text @click="gotorouterviewwithload('/login/facebook')" block  class="text-capitalize " style="background: #3378E9 !important;" >
                                            <v-row>
                                                <v-col cols="2">
                                                    <v-img src="/uploads/facebook.jpg" max-width="25" max-height="25"></v-img>
                                                </v-col>
                                                <v-col  style="margin-top: 4px !important;">Continue with Facebook</v-col>
                                            </v-row>
                                        </v-btn>
                                    </v-row>

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

        <!-- <latestbestsellingtopratedproducts_f > </latestbestsellingtopratedproducts_f> -->
        <footer_f> </footer_f>
    </v-app>
</template>


<script>
    import { mapState } from 'vuex'

    import subheading_f from './subheading.vue'
    // import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import footer_f from './footer.vue'

    import responsive from '../../mixins/responsive.vue'

    export default {
        components:{
            subheading_f, footer_f
        },
        mixins: [responsive],
        mounted() {
            this.topFunction()
            this.$store.dispatch('checkIsLoggedIn')
            // console.log('Component mounted.');
            // console.log(this.$store.getters.welcome)
            this.loading=false;
        },
        data() {
            return {
                showPassword:false,
                loginDetails : {  email: '', password: '' },
                loginDetailsError : { error:false, loginError: '', email: '' , password:'' },
                loginDetailsValid : { valid:false, validMessage: '' },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                ],
                loading:true,
            }
        },
        methods: {

            loginPost(){

                    var _this = this;
                    console.log(this.loginDetails.email)
                    axios.post('/api/auth/login',  this.loginDetails)
                    .then(function (response) {
                        console.log('success !');
                        console.log(response.data.access_token);
                        // window.location.href = '/home/home?token='+response.data.access_token;
                        let token = response.data.access_token;
                        localStorage.setItem('token', token);
                        _this.$store.dispatch('checkIsLoggedIn')
                        // window.location.href = '/';

                        _this.loading=false;
                        _this.loginDetailsError = { error:false, loginError: "" , email: '' , password:''};
                        _this.loginDetailsValid = { valid:true, validMessage: 'Successful ! Please wait..' }

                        if (_this.$route.query.redirectedFrom){
                            _this.$router.go(-1)
                        }
                        else{
                            _this.$router.push('/');
                        }

                        // _this.$router.go(-1);

                    })
                    .catch(function (error) {
                        // console.log('failure !');
                        // console.log(error.response);
                        // console.log(error.response.data.error);
                        // _this.loginDetailsError = { error:true, loginError: error.response.data.error };
                        _this.loginDetailsError = { error:true, loginError: "Invalid email or password", email: error.response.data.email, password: error.response.data.password };
                        _this.loading=false;

                    })
            },

        },
        computed: {
            ...mapState([
                'isLoggedIn', 'userInfo'
            ]),
        },
        metaInfo: {
            titleTemplate: '%s | Login'
        }
    }
</script>
