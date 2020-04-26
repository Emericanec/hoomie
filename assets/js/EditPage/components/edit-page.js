import {Swatches} from 'vue-color'
import draggable from "vuedraggable";

let exampleButton = `
    <button class="btn btn-lg btn-block" :style="{backgroundColor: form.color, color: form.textColor}">{{exampleButtonTitle}}</button>
`;

let template = `
    <section class="content">
        <br>
        <div v-if="isMain">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <button class="btn btn-outline-dark btn-block btn-lg" v-on:click="toAddNewLink()">Add new link</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12" style="margin-top: 8px;">
                    <draggable v-model="linkListDraggable" tag="div" handle=".handle">
                        <div v-for="link in links">
                            <div style="position: relative; width: 0px; height: 0px; left: calc(100% - 30px);">
                                <i v-if="true" class="nav-icon fas fa-arrows-alt-v handle" style="position: absolute; font-size: 1.5rem; top: 12px;"></i>
                            </div>
                            <button class="btn btn-block btn-lg" :style="{marginTop: '8px', backgroundColor: link.settings.backgroundColor, color: link.settings.textColor}" v-on:click="toEditLink(link)">{{link.title}}</button>
                        </div>
                    </draggable>
                </div>
            </div>
        </div>
        <div v-if="isAddNewLink || isEditLink">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="submit" class="btn btn-default" v-on:click="toMain()">Back</button>
                            <button type="submit" class="btn btn-info float-right" v-on:click="saveLink()">Save</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" placeholder="Enter title" v-model="form.title">
                            </div>
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" class="form-control" placeholder="Enter url" v-model="form.url">
                            </div>
                            <div class="form-group">
                                <label>Background Color:</label>
                                <br>
                                <a class="btn btn-dark text-light" v-on:click="toColorChoose()">Change background color</a>
                            </div>
                            <div class="form-group">
                                <label>Text Color:</label>
                                <br>
                                <a class="btn btn-dark text-light" v-on:click="toTextColorChoose()">Change text color</a>
                            </div>
                        </div>
                    </div>
                    ${exampleButton}
                </div>
            </div>
        </div>
        <div v-if="isColorChoose">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <swatches-picker :value="form.color" @input="updateColorValue" style="width: 100%; height:100%;"/>
                </div>
            </div>
        </div>
        <div v-if="isTextColorChoose">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <swatches-picker :value="form.textColor" @input="updateTextColorValue" style="width: 100%; height:100%;"/>
                </div>
            </div>
        </div>
    </section>
`;

const MODE_MAIN = 'main';
const MODE_ADD_NEW_LINK = 'add_new_link';
const MODE_EDIT_LINK = 'edit_link';
const MODE_COLOR_CHOOSE = 'color_choose';
const MODE_TEXT_COLOR_CHOOSE = 'text_color_choose';

export default {
    name: 'edit-page',
    template: template,
    props: {
        id: {
            type: String
        }
    },
    data() {
        return {
            mode: 'main',
            links: [],
            form: {},
            colors: {}
        }
    },
    created() {
        this.getLayout();
    },
    computed: {
        isMain() {
            return this.mode === MODE_MAIN;
        },
        isAddNewLink() {
            return this.mode === MODE_ADD_NEW_LINK;
        },
        isEditLink() {
            return this.mode === MODE_EDIT_LINK;
        },
        isColorChoose() {
            return this.mode === MODE_COLOR_CHOOSE;
        },
        isTextColorChoose() {
            return this.mode === MODE_TEXT_COLOR_CHOOSE;
        },
        exampleButtonTitle()
        {
            return this.form.title || 'Example title';
        },
        linkListDraggable: {
            get() {
                return this.links
            },
            set(value) {
                this.links = value;
                this.saveSort();
            }
        }
    },
    methods: {
        saveLink() {
            let payload = {
                title: this.form.title,
                url: this.form.url,
                backgroundColor: this.form.color,
                textColor: this.form.textColor,
            };

            let url = '/api/page/' + this.id + '/addLink';
            if (null != this.form.id) {
                payload['id'] = this.form.id;
                url = '/api/page/' + this.id + '/editLink/' + this.form.id;
            }

            this.$http.post(url, payload).then(response => {
                this.toMain();
            });
        },
        saveSort() {
            let url = '/api/page/' + this.id + '/saveSort';
            let payload = this.links.map(link => link.id);
            console.log(payload);
            this.$http.post(url, payload).then(response => {
                // todo
            });
        },
        getLayout() {
            this.$http.get('/api/page/' + this.id + '/getLayout').then(response => {
                this.links = response.data;
            });
        },
        updateColorValue(color) {
            this.form.color = color.hex;
            this.toAddNewLink(false);
        },
        updateTextColorValue(color) {
            this.form.textColor = color.hex;
            this.toAddNewLink(false);
        },
        toAddNewLink(initializer = true) {
            if (initializer) {
                this.form = {
                    id: null,
                    url: '',
                    title: '',
                    color: '#007bff',
                    textColor: '#ffffff'
                };
            }
            this.mode = MODE_ADD_NEW_LINK;
        },
        toEditLink(link, initializer = true) {
            if (initializer) {
                this.form = {
                    id: link.id,
                    url: link.settings.url || '',
                    title: link.title || '',
                    color: link.settings.backgroundColor || '#007bff',
                    textColor: link.settings.textColor || '#ffffff'
                };
            }
            this.mode = MODE_EDIT_LINK;
        },
        toMain() {
            this.getLayout();
            this.mode = MODE_MAIN;
        },
        toColorChoose() {
            this.mode = MODE_COLOR_CHOOSE;
        },
        toTextColorChoose() {
            this.mode = MODE_TEXT_COLOR_CHOOSE;
        }
    },
    components: {
        'swatches-picker': Swatches,
        draggable
    }
};