import Vue from 'vue';
import VueResource from "vue-resource"
import Bus from "./eventBus"
Vue.use(VueResource);

let template = `
    <div>
        <input type="file" name="file" ref="fileInput" style="display: none" v-on:change="uploadImage">
        <a class="btn btn-dark btn-sm text-light" @click="$refs.fileInput.click()">+ Add image</a>
    </div>
`;

let imageUploadComponent = new Vue({
    el: '#vue-upload-image',
    template: template,
    data: {
        url: '/app/upload',
    },
    methods: {
        uploadImage($event) {
            let file = $event.target.files[0];
            let formData = new FormData();
            formData.append('file', file);
            this.$http.post( this.url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                let fileObject = {
                    id: response.data.file_id,
                    url: response.data.url
                };

                Bus.dispatchEvent('image-uploaded', fileObject);
            }).catch(response => {
                let errorObject = {
                    message: 'Error while file uploaded'
                };
                Bus.dispatchEvent('error-event', errorObject);
            });
        },
    }
});