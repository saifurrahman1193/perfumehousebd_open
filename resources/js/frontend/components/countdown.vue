<template>
    <div v-if="countdown_data.seconds">
        <v-avatar
            color="red accent-4"
            size="60"
            >
            <span class="white--text "> {{countdown_data.days || 0}} <br> days</span>
        </v-avatar>

        <v-avatar
            color="red accent-4"
            size="60"
            >
            <span class="white--text "> {{countdown_data.hours || 0}} <br> hrs</span>
        </v-avatar>

        <v-avatar
            color="red accent-4"
            size="60"
            >
            <span class="white--text "> {{countdown_data.minutes || 0}} <br> mins</span>
        </v-avatar>

        <v-avatar
            color="red accent-4"
            size="60"
            >
            <span class="white--text "> {{countdown_data.seconds || 0}} <br> secs</span>
        </v-avatar>


    </div>
</template>

<script>
export default {
    data() {
        return {
            todayDate : this.getTodayDateYmd(),
            countdown_data:{}
        }
    },
    mounted() {
        this.countdowndateresultset()
    },
    props: [ "countdowndate" ],
    methods: {

        countdowndateresultset() {
            var _this=this
            setInterval(function() {
                if((_this.countdowndate || '').length>0){
                    var datetimedata = _this.countdowndate+" 24:00:00"
                    console.log('timer = '+datetimedata)
                    _this.countdown_data = _this.get_countdown(datetimedata)
                }
            }, 1000);
        },


    },
    computed: {


    },
}
</script>
