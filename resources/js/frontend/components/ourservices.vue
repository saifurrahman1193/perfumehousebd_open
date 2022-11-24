<template>
    <div id="ourservices" class="pt-12">
        <v-sheet
            class="mt-6"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >
            <v-row
                class=" mt-12"
                align="center"
                justify="center"
                >
                <v-card-title class="display-1 font-weight-bold " id="ourservices">
                    <span  style="color:rgb(74, 74, 74)">Our Services</span>
                </v-card-title>
            </v-row>
            <v-slide-group
            class="pa-4"
            active-class="success"
            show-arrows
            >
                <v-slide-item
                    v-for="service in services"
                    :key="service.serviceId"
                >
                    <v-card
                        class="ma-4"
                        height="320"
                        width="260"
                        @click="gotorouterview('/subservices?'+service.serviceId)"
                    >
                        <v-img
                        class="white--text mx-auto"
                        :src="service.picPath || '/uploads/no_image.png'"
                        lazy-src="/uploads/loader.gif"
                        max-height="190"
                        min-height="190"
                        aspect-ratio contain
                        >
                        </v-img>

                        <v-card-title class="pb-0" style="font-size:18px;">{{service.service}}</v-card-title>
                        <v-divider></v-divider>
                    <v-card-actions>
                            <v-btn
                                color="indigo darken-4"
                                text
                                @click="gotorouterview('/subservices?'+service.serviceId)"
                            >
                                Book Now
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-slide-item>
            </v-slide-group>
        </v-sheet>

        <v-row class="mt-12" align="center" justify="center">
            <v-card-title class="display-1 font-weight-bold" >
                <span style="color:rgb(74, 74, 74)">Our Services</span>
            </v-card-title>
            </v-row>

            <v-row class="w-85 mx-auto">
                <v-col
                    v-for="service in services"
                    :key="service.serviceId"
                    xs="12"
                    sm="6"
                    md="3"
                    cols="auto"
                    width="250"
                    class="mx-auto"
                >

                    <v-hover v-slot:default="{ hover }" open-delay="0" close-delay="100">
                        <v-card :elevation="hover ? 16 : 2"
                            class="pt-1"
                            @click="gotorouterview('/subservices?'+service.serviceId)"
                        >
                            <v-img
                            class="white--text mx-auto"
                            :src="service.picPath || '/uploads/no_image.png'"
                            height="120"
                            width="250"
                            aspect-ratio
                            contain
                            ></v-img>

                            <v-card-title class="pb-0 justify-center" style="font-size:13px;">{{service.service}}</v-card-title>

                        </v-card>
                    </v-hover>
                </v-col>


            </v-row>


    </div>
</template>

<script>


export default {
    data() {
        return {
            services:[]
        }
    },
    mounted() {
        this.getServices();
    },
    methods: {
        getServices(){
            var _this = this;
            axios.get('/api/getServices')
            .then(function (response) {
                _this.services = response.data.data;
            })
            .catch(function (error) {
            })
        },
    },
}
</script>
