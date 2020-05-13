import Vue from 'vue';
import VueResource from "vue-resource"
import AnalyticsPage from "./components/AnalyticsPage"
Vue.use(VueResource);

const template = `
    <analytics-page id="id"></analytics-page>
`;

const selector = '#vue-analytics-page';

const componentExist = null != document.querySelector(selector);
const mountEl = document.querySelector(selector);

if (componentExist) {
    new Vue({
        el: selector,
        template: template,
        components: {
            'analytics-page': AnalyticsPage
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