<template>
    <v-app>
        <subheading_f></subheading_f>

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
                            <h1 class="display-1 mx-auto font-weight-light">Reset Password</h1>
                        </v-card-title>

                        <v-card-text>
                            <v-form  @submit.prevent="resetpasswordPost()" >
                                <v-text-field
                                    name="email"
                                    label="Email"
                                    id="email"
                                    v-model="resetpassword.email"
                                    prepend-icon="person"
                                    :rules="emailRules"
                                    :error-messages="resetpasswordError.email"
                                ></v-text-field>
                                <v-text-field
                                    name="password"
                                    label="New Password"
                                    id="password"
                                    v-model="resetpassword.password"
                                    prepend-icon="lock"
                                    :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                    :type="showPassword ? 'text' : 'password'"
                                    @click:append="showPassword= !showPassword"
                                    :rules="passwordRules"
                                    :error-messages="resetpasswordError.password"

                                ></v-text-field>

                                <v-divider></v-divider>
                                <p v-if="resetpasswordError.error && !loading" class="text-danger mt-1 red--text lighten-1 text-center">{{ resetpasswordError.resetError }}</p>
                                <p v-if="resetpasswordValid.valid && !loading" class="text-success mt-1 green--text lighten-1 text-center">{{ resetpasswordValid.validMessage }}</p>
                                <p v-if="resetpasswordValid.valid && !loading" class="mt-1  lighten-1 text-center">Did't get the mail? Please submit mail again or contact with support.</p>
                                <p v-if="loading" class="text-danger mt-1  lighten-1 text-center">Processing.. Please wait.</p>

                                <v-card-actions>
                                    <v-btn  dark type="submit" text @click="loading=true" block  class="text-capitalize bg-success" >
                                        Submit
                                    </v-btn>
                                </v-card-actions>
                                <p class="text-center hover-text-underline" style="cursor: pointer;" @click="gotorouterview('/login')">
                                <v-icon>navigate_before</v-icon>
                                Back to Login
                                </p>

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
                resetpassword : {  email: '', password: '' },
                resetpasswordError : { error:false, resetError: '', email: '' , password:'' },
                resetpasswordValid : { valid:false, validMessage: '' },
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

            resetpasswordPost(){

                    var _this = this;
                    console.log(this.resetpassword.email)
                    axios.post('/api/auth/resetPassword',  this.resetpassword)
                    .then(function (response) {
                        _this.loading=false;
                        _this.resetpasswordError = { error:false, resetError: "" , email: '' , password:''};
                        _this.resetpasswordValid = { valid:true, validMessage: 'Email successfully sent! Please check your mail.' }
                    })
                    .catch(function (error) {
                        _this.resetpasswordError = { error:true, resetError: "Invalid email", email: error.response.data.email, password: error.response.data.password };
                        _this.resetpasswordValid = { valid:false, validMessage: '' }
                        _this.loading=false;
                    })
            },

        },
        computed: {
            ...mapState([
                'isLoggedIn', 'userInfo'
            ]),
        }
    }
</script>
