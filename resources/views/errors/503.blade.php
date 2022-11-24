{{-- @extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Service Unavailable')) --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfume House BD</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>

<body>

    <div id="app">
        <v-app>
            <!-- <h1 id="cd">Perfume House BD</h1> -->
            <p style="">
                <br>
            </p>
            <v-card class="mx-auto" max-width="100%" max-height="100%">
                <v-img src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg" height="200px"></v-img>

                <v-card-title>
                    <h1 style="margin: auto;  width: 50%; text-align: center; font-weight: 900;">
                        COMING SOON
                    </h1>
                </v-card-title>

                <v-card-subtitle>
                    <h2 style="margin-left: auto; margin-right: auto; margin-top: 10px; margin:bottom: 10px;  width: 50%;  border: 3px solid green;  padding: 10px; text-align: center; color: red" v-text="cd_data">

                    </h2>
                </v-card-subtitle>


                <v-card-actions>

                    <v-spacer></v-spacer>

                    <v-btn icon @click="show = !show">
                        <v-icon v-text="show ? 'mdi-chevron-up' : 'mdi-chevron-down'"></v-icon>

                    </v-btn>
                </v-card-actions>

                <v-expand-transition>
                    <div v-show="show">
                        <v-divider></v-divider>

                        <v-card-text>
                            <p class="text-center">
                                Perfume House BD site is under construction. Please come back again after <span v-text="cd_data"></span>. We will be waiting.
                            </p>
                        </v-card-text>
                    </div>
                </v-expand-transition>
            </v-card>
        </v-app>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            mounted() {
                this.get_countdown()
            },
            data() {
                return {
                    days: '',
                    hours: '',
                    minutes: '',
                    seconds: '',
                    now: '',
                    distance: '',
                    x: '',
                    countDownDate: '',
                    cd_data: '',
                }
            },
            methods: {
                get_countdown() {
                    // Set the date we're counting down to
                    this.countDownDate = new Date("May 1, 2021 12:12:00").getTime();
                    var _this = this
                        // Update the count down every 1 second
                    this.x = setInterval(function() {

                        // Get today's date and time
                        _this.now = new Date().getTime();

                        // Find the distance between now and the count down date
                        _this.distance = _this.countDownDate - _this.now;

                        // Time calculations for days, hours, minutes and seconds
                        _this.days = Math.floor(_this.distance / (1000 * 60 * 60 * 24));
                        _this.hours = Math.floor((_this.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        _this.minutes = Math.floor((_this.distance % (1000 * 60 * 60)) / (1000 * 60));
                        _this.seconds = Math.floor((_this.distance % (1000 * 60)) / 1000);

                        // Output the result in an element with id="demo"
                        _this.cd_data = _this.days + "d " + _this.hours + "h " +
                            _this.minutes + "m " + _this.seconds + "s ";

                        // If the count down is over, write some text
                        if (_this.distance < 0) {
                            clearInterval(_this.x);
                            _this.cd_data = "EXPIRED";
                        }
                    }, 1000);
                },
            },
            vuetify: new Vuetify(),
        })
    </script>


</body>

</html>

