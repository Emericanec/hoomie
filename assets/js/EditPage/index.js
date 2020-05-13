import Vue from 'vue';
import VueResource from "vue-resource"
import EditPage from './components/editPage';

Vue.use(VueResource);

const template = `
    <edit-page :id="id"></edit-page>
`;

const selector = '#vue-edit-page';

const componentExist = null != document.querySelector(selector);
const mountEl = document.querySelector(selector);

if (componentExist) {
    new Vue({
        el: selector,
        template: template,
        components: {
            'edit-page': EditPage
        },
        data() {
            return {
                id: null
            }
        },
        created() {
            this.id = mountEl.dataset.id;
        }
    });
}