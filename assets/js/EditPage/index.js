import Vue from 'vue';
import VueResource from "vue-resource"
import EditPage from './components/edit-page';
Vue.use(VueResource);

let template = `
    <edit-page :id="id"></edit-page>
`;

let editPageComponent = new Vue({
    el: '#vue-edit-page',
    template: template,
    data() {
        return {
            id: null
        }
    },
    created() {
        this.id = window.location.pathname.replace('/app/page/edit/', '');
    },
    components: {
        'edit-page': EditPage
    }
});