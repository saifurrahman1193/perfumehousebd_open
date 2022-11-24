<template>
  <v-footer class="footer" color="#333e4f"  padless >
    <!-- <v-card
      class="flex grey lighten-3 black--text"
      flat
      tile
    >
      <v-card-title >
        <v-btn class="mx-1" icon :href="companyInfo.facebookLink" target="_blank" color="indigo" >
          <v-icon size="24px">{{ companyInfo.facebookIconClass }}</v-icon>
        </v-btn>
        <v-btn class="mx-1" icon :href="companyInfo.twitterLink" target="_blank" color="blue" >
          <v-icon size="24px">{{ companyInfo.twitterIconClass }}</v-icon>
        </v-btn>
        <v-btn class="mx-1" icon :href="companyInfo.instagramLink" target="_blank" color="pink accent-4" >
          <v-icon size="24px">{{ companyInfo.instagramIconClass }}</v-icon>
        </v-btn>
        <v-btn class="mx-1" icon :href="companyInfo.googlePlusLink" target="_blank" color="red darken-3" >
          <v-icon size="24px">{{ companyInfo.googlePlusIconClass }}</v-icon>
        </v-btn>
        <v-btn class="mx-1" icon :href="companyInfo.linkedinLink" target="_blank" color="blue darken-1" >
          <v-icon size="24px">{{ companyInfo.linkedinIconClass }}</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
        <h6>Copyright &copy; {{ new Date().getFullYear() }} EasySheba . All rights reserved.</h6>
      </v-card-title>
    </v-card>-->

    <v-container class="flex black--text" >
      <!-- <v-row class="topHover" @click="topFunction">
        <v-btn class="mx-auto" color="primary" text>Back to top</v-btn>
      </v-row> -->

      <!-- <v-btn
            fab
            dark
            fixed
            bottom
            right
            color="#C9151B"
            @click="gotorouterview('/chat')"
            :style="isSMALT?'margin-right:38px;':'margin-right:66px;'"
            v-bind="btnSize"
            v-if="userInfo.id"
          >
            <v-icon>chat</v-icon>
      </v-btn> -->


      <v-btn
            fab
            dark
            fixed
            bottom
            right
            color="#C9151B"
            @click="topFunction"
            style="margin-right:75px;"
          >
            <v-icon>keyboard_arrow_up</v-icon>
      </v-btn>

    <!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>




      <v-container >
        <v-row >

            <v-col :class="isSMALT?'py-0':''" xs="12" sm="6" md="3"  cols="auto"  >
                <div >
                    <div class="d-flex mx-auto" dark>
                        <v-img :src="companyInfo.logoPath || '/uploads/no_image.png'" height="60" aspect-ratio contain  class="mx-auto"></v-img>
                    </div>
                    <div class="d-flex">
                        <v-card-title class="d-inline-block mx-auto" >
                            <v-btn
                                class="mx-0"
                                icon
                                :href="companyInfo.facebookLink"
                                target="_blank"
                                color="indigo"
                            >
                                <v-icon size="24px">{{ companyInfo.facebookIconClass }}</v-icon>
                            </v-btn>
                            <v-btn
                                class="mx-0"
                                icon
                                :href="companyInfo.twitterLink"
                                target="_blank"
                                color="blue"
                            >
                                <v-icon size="24px">{{ companyInfo.twitterIconClass }}</v-icon>
                            </v-btn>
                            <v-btn
                                class="mx-0"
                                icon
                                :href="companyInfo.instagramLink"
                                target="_blank"
                                color="pink accent-4"
                            >
                                <v-icon size="24px">{{ companyInfo.instagramIconClass }}</v-icon>
                            </v-btn>
                            <v-btn
                                class="mx-0"
                                icon
                                :href="companyInfo.googlePlusLink"
                                target="_blank"
                                color="red darken-3"
                            >
                                <v-icon size="24px">{{ companyInfo.googlePlusIconClass }}</v-icon>
                            </v-btn>
                            <v-btn
                                class="mx-0"
                                icon
                                :href="companyInfo.linkedinLink"
                                target="_blank"
                                color="blue darken-1"
                            >
                                <v-icon size="24px">{{ companyInfo.linkedinIconClass }}</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                        </v-card-title>
                    </div>
                    <div class="d-flex footer_font_color">
                        <h6 class="d-inline-block  mx-auto"
                        >Copyright &copy; {{ new Date().getFullYear() }} {{ companyInfo.name }} . All rights reserved.
                        </h6>
                    </div>
                </div>
            </v-col>




            <v-col :class="isSMALT?'py-0':''" xs="12" sm="6" md="3"  cols="auto"  light >
                <hr style="width:83vw;" v-if="isXS">
                <v-list style="background-color:transparent;" dense :class="isSMALT?'py-0':''" >
                    <v-list-item @click="gotorouterview('/')" dense>Home</v-list-item>
                    <v-list-item @click="gotorouterview('/pages/'+aboutus.pageSlug); setPageData(aboutus.pageSlug)" dense>About Us</v-list-item>
                    <v-list-item @click="gotorouterview('/pages/'+branches.pageSlug); setPageData(branches.pageSlug)" dense>Branches</v-list-item>
                </v-list>
            </v-col>

            <v-col :class="isSMALT?'py-0':''" xs="12" sm="6" md="3"  cols="auto"  light >
                <hr style="width:83vw;" v-if="isXS">
                <v-list style="background-color:transparent;" dense :class="isSMALT?'py-0':''">
                    <v-list-item @click="gotorouterview('/pages/'+ourprocedures.pageSlug); setPageData(ourprocedures.pageSlug)" dense>Order Procedures</v-list-item>
                    <v-list-item @click="gotorouterview('/pages/'+paymentmethod.pageSlug); setPageData(paymentmethod.pageSlug)" dense>Payment Method</v-list-item>
                    <v-list-item @click="gotorouterview('/pages/'+termscondition.pageSlug); setPageData(termscondition.pageSlug)" dense>Terms & Conditions</v-list-item>
                    <v-list-item @click="gotorouterview('/pages/'+privacypolicy.pageSlug); setPageData(privacypolicy.pageSlug)" dense>Privacy Policy</v-list-item>
                </v-list>
            </v-col>

            <v-col :class="isSMALT?'py-0':''" xs="12" sm="6" md="3"  cols="auto" light>
                <hr style="width:83vw;" v-if="isXS">
                <v-list style="background-color:transparent;" dense :class="isSMALT?'py-0':''">
                    <v-list-item class="font-weight-bold title" >Contact Us</v-list-item>
                    <v-list-item v-html="companyInfo.address" v-if="companyInfo.address"></v-list-item>
                    <v-list-item>
                        Email: {{companyInfo.email}} <br>
                        {{companyInfo.phone}}
                    </v-list-item>
                </v-list>
            </v-col>



        </v-row>

        <!-- <v-row>
          <v-col cols="6">
            <v-row class>
              <p class="p-3">Privacey Policy</p>
              <p class="p-3">Terms of Service</p>
            </v-row>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="6" class="d-flex">
            <h6
              class="d-inline-block ml-auto"
            >Copyright &copy; {{ new Date().getFullYear() }} EasySheba . All rights reserved.</h6>
          </v-col>
        </v-row> -->
      </v-container>
    </v-container>
  </v-footer>
</template>


<script>
import { mapState } from 'vuex'

import "@fortawesome/fontawesome-free/css/all.css"; // Ensure you are using css-loader
import Vue from "vue";
import Vuetify from "vuetify";
import responsive from '../../mixins/responsive.vue'

Vue.use(Vuetify, {
  iconfont: "fa"
});
export default {
  mounted() {
    this.getPages();


    // facebook
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "105365618177758");
      chatbox.setAttribute("attribution", "biz_inbox");
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v11.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
  },
    mixins: [responsive],
  data() {
    return {
      pages:[]
    };
  },
  methods: {

    topFunction() {
      // document.body.scrollTop = 0; // For Safari
      // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
      window.scrollTo({ top: 0, behavior: "smooth" });
    },
    getPages(){
        var _this = this;
        axios.get('/api/getPages')
        .then(function (response) {
            _this.pages = response.data.data;
        })
        .catch(function (error) {
        })
    },
    setPageData(pageSlug){
        this.$store.dispatch('setDynamicPageInfo', {pageSlug:pageSlug})
    }

  },
  computed: {
        aboutus:function(){
            return this.pages.filter(function(page){
                return page.pageId==1
            })[0]
        },
        branches:function(){
            return this.pages.filter(function(page){
                return page.pageId==2
            })[0]
        },
        ourprocedures:function(){
            return this.pages.filter(function(page){
                return page.pageId==3
            })[0]
        },
        paymentmethod:function(){
            return this.pages.filter(function(page){
                return page.pageId==4
            })[0]
        },
        termscondition:function(){
            return this.pages.filter(function(page){
                return page.pageId==5
            })[0]
        },
        privacypolicy:function(){
            return this.pages.filter(function(page){
                return page.pageId==6
            })[0]
        },
        btnSize: function(){
            return this.isSMALT?{'x-small':true} : {};
        },

        ...mapState([
            'userInfo', 'companyInfo'
        ]),
  }
};
</script>

<style scoped>
 /* scoped = limits only to this componenet */

.theme--light.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled), .footer_font_color{
    color: #9ca9be !important;
}



</style>


