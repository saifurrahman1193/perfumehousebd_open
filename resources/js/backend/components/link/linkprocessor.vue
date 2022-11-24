<template>

    <div>
        <v-chip :color="dataobject.color || 'primary'" outlined
            v-if="dataobject.link && !isProcessing"
            @click="hitLink"
            :doawnload="dataobject.isDownloadable || false"
        >
            <v-icon v-if="dataobject.icon"  :color="((dataobject.icon || {}).color) || dataobject.color || 'primary'" >{{ dataobject.icon.name }}</v-icon> &nbsp;
            {{ dataobject.name || 'Click Here' }}
        </v-chip>

        <v-chip :color="dataobject.color || 'primary'" outlined
            v-if="dataobject.link && isProcessing"
        >
            <v-icon v-if="dataobject.icon"  :color="((dataobject.icon || {}).color) || dataobject.color || 'primary'" >{{ dataobject.icon.name }}</v-icon> &nbsp;
            {{ dataobject.processName || 'Processing...' }} &nbsp;
            <v-progress-circular
                indeterminate
                color="orange"
                size="20"
            ></v-progress-circular>
        </v-chip>

        <div v-if="dataobject.link && !isProcessing && isReadyToDownload && dataobject.downloadLink">
            <br>
            <a :href="dataobject.downloadLink || ''"  download >
                Click To Download
            </a>
        </div>

    </div>

</template>

<script>

    export default {
        props:[
            'dataobject',
        ],
        data() {
            return {
                isProcessing: false,
                isSuccess: false,
                isFailure: false,

                isReadyToDownload: false
            }
        },
        mounted() {
            if (((this.dataobject)||{}).link)
            {
                console.log('link ')
            }

        },
        methods: {
            hitLink(){
                // link='', download=false, type='GET', obj={}

                if(this.dataobject.link)
                {
                    this.isProcessing = true

                    var _this=this
                    axios.get(this.dataobject.link)
                    .then(function (response) {

                        _this.isProcessing=false
                        _this.isSuccess=true
                        _this.isFailure=false
                        _this.isReadyToDownload=true


                        if(_this.dataobject.isAlertShow && !_this.dataobject.downloadLink){
                            alert('Success')
                        }
                    })
                    .catch(function (error) {
                        _this.isProcessing=false
                        _this.isSuccess=false
                        _this.isFailure=true
                        _this.isReadyToDownload=false

                        if(_this.dataobject.isAlertShow){
                            alert('Failure')
                        }
                    })
                }

            }
        },
    }
</script>
