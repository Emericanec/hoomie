import Vue from 'vue';
import Bus from "./eventBus"

let template = `
    <div class="row">
        <div class="col-md-4 col-sm-6" v-for="file in files" :key="file.id" style="padding-bottom: 15px;">
            <img :src="file.url" style="width: 100%;">
        </div>
    </div>
`;

let imageUploadComponent = new Vue({
    el: '#vue-image-gallery',
    template: template,
    data: function () {
        return {
            files: [],
        }
    },
    created() {
        Bus.addEventListener('image-uploaded', event => {
            this.files.push(event.detail);
            console.log(event.detail);
            console.log(this.files);
        });
    },
    methods: {}
});