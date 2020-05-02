import Vue from 'vue';
import VueResource from "vue-resource"
import EditPage from './components/editPage';

Vue.use(VueResource);

const template = `
    <edit-page :id="id"></edit-page>
`;

const selector = '#vue-edit-page';

const componentExist = null != document.querySelector(selector);

if (componentExist) {
    let editPageComponent = new Vue({
        el: selector,
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
}