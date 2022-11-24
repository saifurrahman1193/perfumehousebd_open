<template>
    <div>
        <v-row>
            <v-card>
                <v-app-bar app elevation="1" class="hidden-sm-and-down  px-5"
            >
                <v-app-bar-nav-icon class="ml-1">
                    <v-img height="60" aspect-ratio contain src="/uploads/system/mini_logo.png" @click="gotorouterview('/')"></v-img>
                </v-app-bar-nav-icon>
                <v-spacer></v-spacer>


                <!-- <v-btn to="/#ourservices" text @click="gotorouterviewwithload('/?#ourservices')">
                    Services
                </v-btn>

                <v-btn to="/#aboutus" text @click="gotorouterviewwithload('/?#aboutus')">
                    About Us
                </v-btn>

                <v-btn to="/#whychooseeasysheba" text @click="gotorouterviewwithload('/?#whychooseeasysheba')">
                    Why Choose Us
                </v-btn>

                <v-btn to="/#howeasyshebaworks" text @click="gotorouterviewwithload('/?#howeasyshebaworks')">
                    How EasySheba Works
                </v-btn> -->



                <v-spacer></v-spacer>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <div class="mr-5" text  @click="gotorouterview('/checkout')" v-on="on" style="cursor: pointer;">
                                <v-badge  color="green" :content="getCartList.length" :value="getCartList.length">
                                    <v-icon  color="#C9151B" v-if="getCartList.length" >add_shopping_cart</v-icon>
                                    <v-icon  color="grey" v-else >shopping_cart</v-icon>
                                </v-badge>
                        </div>
                    </template>

                    <span>Go to checkout</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <div class="mr-5" text   v-on="on"  style="cursor: pointer;">
                                <v-badge  color="green" :content="getCompareList.length" :value="getCompareList.length">
                                    <v-icon color="#C9151B" v-if="getCompareList.length" >compare</v-icon>
                                    <v-icon color="grey" v-else >compare</v-icon>
                                </v-badge>
                        </div>
                    </template>

                    <span>Go to compare list</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <div class="mr-5" text   v-on="on"  style="cursor: pointer;">
                                <v-badge  color="green" :content="getWishList.length" :value="getWishList.length">
                                    <v-icon color="#C9151B" v-if="getWishList.length" >favorite</v-icon>
                                    <v-icon color="grey" v-else >favorite_border</v-icon>
                                </v-badge>
                        </div>
                    </template>

                    <span>Go to wish list</span>
                </v-tooltip>




                <v-btn
                    outlined
                    color="indigo darken-1"
                    v-if="isLoggedIn==false"
                    @click="gotorouterview('/login')"
                    >
                        Sign In
                </v-btn>

                <v-menu
                    offset-y
                    transition="slide-x-transition"
                    bottom
                    >
                    <template v-slot:activator="{ on }">
                        <v-btn text icon>
                            <v-avatar v-if="isLoggedIn" color="indigo" v-on="on">
                                <v-icon color="white" v-if="!userInfo.photoPath">person</v-icon>
                                <img
                                    :src="userInfo.photoPath"
                                    :alt="((userInfo.name).match(/\b(\w)/g)).join('')"
                                    v-if="userInfo.photoPath"
                                >
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-list min-width="220">
                        <v-list-item
                        v-for="(item, index) in profileMenu"
                        :key="index"
                        @click="gotorouterview(item.link)"
                        dense
                        >
                            <v-list-item-title  class="pl-1">{{ item.title }}</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item>

                        <v-list-item @click="gotorouterview('/orderhistory' )" dense>
                            <v-list-item-title class="pl-1">Order History</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>list_alt</v-icon>
                        </v-list-item>

                        <v-divider></v-divider>
                        <v-list-item
                        @click="logout()"
                            dense
                        >
                            <v-list-item-title class="pl-1">Sign Out</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>power_settings_new</v-icon>
                        </v-list-item>
                    </v-list>
                </v-menu>


            </v-app-bar>
            </v-card>



            <v-card width="500" class="hidden-md-and-up mx-auto" >
                <v-app-bar fixed>
                    <v-toolbar dense elevation="0">


                    <v-app-bar-nav-icon>
                        <v-img
                        src="/uploads/system/mini_logo.png"
                        height="45"
                        aspect-ratio
                        contain
                        class="ml-6"
                        ></v-img>
                    </v-app-bar-nav-icon>
                    <v-spacer></v-spacer>
                    <v-btn class="mr-1" text v-if="getCartListCount">
                        <v-badge  color="green" :content="getCartListCount" >
                            <v-icon>shopping_cart</v-icon>
                        </v-badge>
                    </v-btn>




                    <v-app-bar-nav-icon
                        v-show="subheading_dropdown_list==false"
                        @click="subheading_dropdown_list=true"
                    ></v-app-bar-nav-icon>
                    <v-btn
                        text
                        rounded
                        v-show="subheading_dropdown_list==true"
                        @click="subheading_dropdown_list=false"
                    >
                        <v-icon>close</v-icon>
                    </v-btn>
                    </v-toolbar>


                </v-app-bar>

                <v-list elevation="5" dense v-show="subheading_dropdown_list==true" class="hidden-md-and-up fluid" style="margin-top:57px; position:fixed; width:100%; z-index:100;" >
                <v-list-item-group class="pl-3">
                    <!-- <v-list-item>
                    <v-list-item-title to="/#ourservices" @click="gotorouterviewwithload('/?#ourservices')">Services</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                    <v-list-item-title to="/#aboutus" @click="gotorouterviewwithload('/?#aboutus')">About Us</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                    <v-list-item-title to="/#whychooseeasysheba" @click="gotorouterviewwithload('/?#whychooseeasysheba')">Why Choose Us</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                    <v-list-item-title to="/#howeasyshebaworks" @click="gotorouterviewwithload('/?#howeasyshebaworks')">How EasySheba Works</v-list-item-title>
                    </v-list-item> -->

                    <v-list-item v-if="!isLoggedIn">
                        <v-list-item-title to="/login" v-if="!isLoggedIn" @click="gotorouterview('/login')">Sign In</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                    <v-menu offset-y transition="slide-x-transition" bottom v-if="isLoggedIn">
                        <template v-slot:activator="{ on }">
                        <v-list-item-title v-on="on">Profile</v-list-item-title>
                        </template>
                        <v-list min-width="220">
                        <v-list-item
                            v-for="(item, index) in profileMenu"
                            :key="index"
                            @click="gotorouterviewwithload(item.link)"
                            dense
                        >
                            <v-list-item-title class="pl-1">{{ item.title }}</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item>

                        <v-list-item @click="gotorouterview('/orderhistory')" dense>
                            <v-list-item-title class="pl-1">Order History</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>list_alt</v-icon>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="logout()" dense>
                            <v-list-item-title class="pl-1">Sign Out</v-list-item-title>
                            <v-spacer></v-spacer>
                            <v-icon>power_settings_new</v-icon>
                        </v-list-item>
                        </v-list>
                    </v-menu>
                    </v-list-item>


                </v-list-item-group>
                </v-list>
            </v-card>


        </v-row>
    </div>
</template>


<script>

import { mapState, mapGetters } from 'vuex'

export default {
    mounted() {
        this.$store.dispatch('checkIsLoggedIn')
        this.$store.dispatch('setCurrentUserWishLists')
    },
    data() {
        return {
            token:localStorage.getItem('token'),
            subheading_dropdown_list : false,
            profileMenu: [
                { title: 'Profile', link:'/profile', icon:'person'},
                { title: 'Change Password', link:'/changepassword', icon:'lock'},
                // { title: 'Order History', link:'/orderhistory', icon:'list_alt'},
            ],

        }
    },
    computed: {
        ...mapState([
            'isLoggedIn', 'userInfo',
        ]),
        ...mapGetters([
            'getCartList', 'getCartListCount', 'getCompareList', 'getWishList'
        ])
    },
    methods: {
        logout(){
            var _this = this;
            axios.post('/api/auth/logout?token='+this.token)
            .then(function (response) {
                // window.location.href = '/login';
                _this.$store.dispatch('checkIsLoggedIn')

                this.$router.push('/login');
                // _this.gotorouterview('/login');
                localStorage.removeItem('token')
            })
            .catch(function (error) {
                // window.location.href = '/login';
                // this.$router.push('/login');
                _this.gotorouterview('/login');
                localStorage.removeItem('token')
            })
        },
    },
}
</script>

