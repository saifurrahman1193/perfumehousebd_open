<template>
            <v-system-bar  app color="#C9151B" dark   v-if="!hidden" class="hidden-sm-and-down">

                <span class="ml-4">{{ companyInfo.topSystemQuote }}</span>
                <v-spacer></v-spacer>
                <span
                    @click="gotorouterview('/productlist')"
                    style="cursor: pointer;"
                >Shop</span>
                <v-icon class="ma-2">more_vert</v-icon>

                <span class="mr-4"
                    @click="gotorouterview('/wishlist')"
                    style="cursor: pointer;"
                >Wishlist</span>

                <!-- <span class="mr-2">Follow us on: </span> -->
                <v-btn
                    class="mx-0 mr-2"
                    icon
                    :href="companyInfo.facebookLink"
                    target="_blank"
                    color="red lighten-4"
                    x-small
                >
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on">{{ companyInfo.facebookIconClass }}</v-icon>
                        </template>
                        <span>Facebook</span>
                    </v-tooltip>
                </v-btn>

                <v-btn
                class="mx-0 mr-2"
                icon
                :href="companyInfo.twitterLink"
                target="_blank"
                color="red lighten-4"
                x-small
                >
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" >{{ companyInfo.twitterIconClass }}</v-icon>
                    </template>
                    <span>Twitter</span>
                </v-tooltip>
                </v-btn>
                <v-btn
                class="mx-0 mr-2"
                icon
                :href="companyInfo.instagramLink"
                target="_blank"
                color="red lighten-4"
                x-small
                >
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" >{{ companyInfo.instagramIconClass }}</v-icon>
                    </template>
                    <span>Instagram</span>
                </v-tooltip>
                </v-btn>
                <v-btn
                class="mx-0 mr-2"
                icon
                :href="companyInfo.googlePlusLink"
                target="_blank"
                color="red lighten-4"
                x-small
                >
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" >{{ companyInfo.googlePlusIconClass }}</v-icon>
                    </template>
                    <span>Google Plus</span>
                </v-tooltip>
                </v-btn>
                <v-btn
                class="mx-0 mr-2"
                icon
                :href="companyInfo.linkedinLink"
                target="_blank"
                color="red lighten-4"
                x-small
                >
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" >{{ companyInfo.linkedinIconClass }}</v-icon>
                    </template>
                    <span>Linkedin</span>
                </v-tooltip>
                </v-btn>
            </v-system-bar>
</template>


<script>
import { mapState } from 'vuex'

import "@fortawesome/fontawesome-free/css/all.css"; // Ensure you are using css-loader
import Vue from "vue";
import Vuetify from "vuetify";
Vue.use(Vuetify, {
  iconfont: "fa"
});
export default {
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
    },
    data() {
        return {
            hidden:false
        };
    },
    computed: {
        ...mapState([
            'companyInfo'
        ]),
    },
  methods: {
    handleScroll() {
      if (window.scrollY >= 100) {
          this.hidden=true
      } else {
          this.hidden=false
      }
    },


  }
};
</script>


