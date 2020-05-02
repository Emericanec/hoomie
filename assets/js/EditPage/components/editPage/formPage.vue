<template>
    <div>
        <div class="card form-page-card" v-if="isColorChoose || isTextColorChoose">
            <div class="card-header">
                <button type="submit" class="btn btn-default" v-on:click="toMain">Back</button>
            </div>
            <div class="card-body">
                <swatches-picker v-if="isColorChoose" class="form-page-color-picker" :value="form.color"
                                 @input="updateColorValue"/>
                <swatches-picker v-if="isTextColorChoose" class="form-page-color-picker" :value="form.textColor"
                                 @input="updateTextColorValue"/>
            </div>
        </div>
        <div class="card form-page-card" v-if="isMain">
            <div class="card-header">
                <button type="submit" class="btn btn-default" v-on:click="backCallback">Back</button>
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
                    <button type="button" class="btn btn-outline-dark" v-on:click="toColorChoose()">
                        Choose Background Color
                    </button>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-dark" v-on:click="toTextColorChoose()">
                        Choose Text Color
                    </button>
                </div>
                <div class="form-group">
                    <label>Size:</label>
                    <br>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-dark" v-on:click="changeSize(4)">Small</button>
                        <button type="button" class="btn btn-outline-dark" v-on:click="changeSize(6)">Medium</button>
                        <button type="button" class="btn btn-outline-dark" v-on:click="changeSize(12)">Large</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 8px;">
            <div :class="getColClass(form)">
                <button class="btn btn-lg btn-block" :style="{backgroundColor: form.color, color: form.textColor}">
                    {{exampleButtonTitle}}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {Swatches} from "vue-color";

    const MODE_MAIN = 'main';
    const MODE_COLOR_CHOOSE = 'color_choose';
    const MODE_TEXT_COLOR_CHOOSE = 'text_color_choose';

    export default {
        name: "formPage",
        props: {
            pageId: {
                type: String
            },
            backCallback: {
                type: Function
            },
            editLink: {
                type: Object
            }
        },
        beforeMount() {
            if (this.editLink.hasOwnProperty('id')) {
                this.form = {
                    id: this.editLink.id,
                    url: this.editLink.settings.url || '',
                    title: this.editLink.title || '',
                    size: this.editLink.settings.size || 12,
                    color: this.editLink.settings.backgroundColor || '#007bff',
                    textColor: this.editLink.settings.textColor || '#ffffff'
                };
            } else {
                this.form = {
                    id: null,
                    url: '',
                    title: '',
                    size: 12,
                    color: '#007bff',
                    textColor: '#ffffff'
                };
            }
        },
        data() {
            return {
                mode: MODE_MAIN,
                form: {}
            }
        },
        computed: {
            isMain() {
                return this.mode === MODE_MAIN;
            },
            isColorChoose() {
                return this.mode === MODE_COLOR_CHOOSE;
            },
            isTextColorChoose() {
                return this.mode === MODE_TEXT_COLOR_CHOOSE;
            },
            exampleButtonTitle() {
                return this.form.title || 'Example title';
            }
        },
        methods: {
            saveLink() {
                let payload = {
                    title: this.form.title,
                    url: this.form.url,
                    size: this.form.size,
                    backgroundColor: this.form.color,
                    textColor: this.form.textColor,
                };

                let url = '/api/page/' + this.pageId + '/addLink';
                if (null != this.form.id) {
                    payload['id'] = this.form.id;
                    url = '/api/page/' + this.pageId + '/editLink/' + this.form.id;
                }

                this.$http.post(url, payload).then(response => {
                    this.backCallback();
                });
            },
            getColClass(link) {
                let size = link.size || link.settings.size || 12;
                size = parseInt(size);
                return 'col-' + size + ' offset-' + ((12 - size) / 2);
            },
            updateColorValue(color) {
                this.form.color = color.hex;
                this.toMain();
            },
            updateTextColorValue(color) {
                this.form.textColor = color.hex;
                this.toMain();
            },
            changeSize(size) {
                this.form.size = size;
            },
            toMain() {
                this.mode = MODE_MAIN;
            },
            toColorChoose() {
                this.mode = MODE_COLOR_CHOOSE;
            },
            toTextColorChoose() {
                this.mode = MODE_TEXT_COLOR_CHOOSE;
            },
        },
        components: {
            'swatches-picker': Swatches,
        }
    }
</script>

<style scoped>
    .form-page-card button {
        font-weight: 700;
    }

    .form-page-color-picker {
        width: 100%;
        height: 100%;
        box-shadow: none;
    }
</style>