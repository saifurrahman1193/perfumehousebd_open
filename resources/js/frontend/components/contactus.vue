<template>
    <div id="aboutus">
        <v-sheet
            class="mt-6"
            elevation="0"
            max-width="100%"
        >
            <v-row
                class=" mt-12"
                align="center"
                justify="center"
                >
                <v-card-title class="display-1 font-weight-bold " >
                    <span  style="color:rgb(74, 74, 74)">About EasySheba</span>
                </v-card-title>
                <v-card-text class="text--primary " >
                    <v-row class="mx-auto">
                        <v-col  xs="12" sm="6" cols="auto" class="mx-auto"
                            v-if="companyinfo.aboutPicPath">
                                <v-img aspect-ratio contain
                                :src="companyinfo.aboutPicPath"
                                max-height="200"
                                max-width="200"
                                class="mx-auto"
                                >
                                </v-img>
                        </v-col>
                        <v-col xs="12" sm="6" cols="auto">

                            <p class="font-italic subtitle-1 grey--text text--darken-2 pt-md-10" style="line-height:35px;">
                                <!-- <span class="display-1">“</span>
                                {{companyinfo.about}}<span class="display-1">”</span> -->
                                <v-icon size="24px" style="margin-right:7px;">fas fa-quote-left</v-icon>
                                {{companyinfo.about}}
                                <v-icon size="24px"  style="margin-left:7px;">fas fa-quote-right</v-icon>
                            </p>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-row>
        </v-sheet>
    </div>
</template>

<script>
export default {
    data() {
        return {
            companyinfo: {},
        }
    },
    mounted() {
        this.topFunction()
        this.getCompanyInfo();
    },
    methods: {
        getCompanyInfo(){
            var _this = this;
            axios.get('/api/getCompanyInfo')
            .then(function (response) {
                _this.companyinfo = response.data.data;
            })
            .catch(function (error) {
            })
        },
    },
}

import Vue from "vue";
import Vuetify from "vuetify";

Vue.use(Vuetify, {
  iconfont: "fa"
});

</script>
