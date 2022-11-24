<template>
    <div class="mt-6">

      <v-sheet
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >

          <v-row class="hidden-sm-and-down">
          <v-carousel
            cycle
            show-arrows-on-hover
            class="mt-12"

            >
            <v-carousel-item
              v-for="advertisementslider in advertisementsliders"
              :key="advertisementslider.advertisementSliderId"
              >

                <v-row
                  class="fill-height"
                  align="center"
                  justify="center"
                >
                  <v-img
                  :src="advertisementslider.picPath || '/uploads/no_image.png'"
                  >
                    <template v-slot:placeholder>
                        <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                        >
                            <v-progress-circular indeterminate color="#C9151B"></v-progress-circular>
                        </v-row>
                    </template>
                  </v-img>
                </v-row>
            </v-carousel-item>
          </v-carousel>
        </v-row>

        <v-row class="hidden-md-and-up hidden-xs-only">
          <v-carousel
            cycle
            show-arrows-on-hover
            height="50%"

            >
            <v-carousel-item
              v-for="advertisementslider in advertisementsliders"
              :key="advertisementslider.advertisementSliderId"
              >
              <v-sheet
              height="100%"
              color="white"
              >
                <v-row
                  class="fill-height"
                  align="center"
                  justify="center"
                >
                  <v-img
                  :src="advertisementslider.picPath || '/uploads/no_image.png'"
                  >
                  </v-img>
                </v-row>
              </v-sheet>
            </v-carousel-item>
          </v-carousel>
        </v-row>


        <v-row class="hidden-sm-and-up">
          <v-carousel
            cycle
            show-arrows-on-hover
            height="25%"
            hide-delimiters
            hide-delimiter-background
            >
            <v-carousel-item
              v-for="advertisementslider in advertisementsliders"
              :key="advertisementslider.advertisementSliderId"
              >
              <v-sheet
              height="100%"
              color="white"
              >
                <v-row
                  class="fill-height"
                  align="center"
                  justify="center"
                >
                  <v-img
                  :src="advertisementslider.picPath || '/uploads/no_image.png'"
                  >
                  </v-img>
                </v-row>
              </v-sheet>
            </v-carousel-item>
          </v-carousel>
        </v-row>
      </v-sheet>


    </div>
</template>

<script>
export default {
  data() {
        return {
            advertisementsliders:[]
        }
    },
    mounted() {
        this.getAdvertisementSliders();
    },
    methods: {
        getAdvertisementSliders(){
          console.log('called')
            var _this = this;
            axios.get('/api/getAdvertisementSliders')
            .then(function (response) {
                _this.advertisementsliders = response.data.data;
            })
            .catch(function (error) {
            })
        }
    },
}
</script>
