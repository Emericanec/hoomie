<template>
    <div>
        <div class="card form-page-card" v-if="isColorChoose || isTextColorChoose || isIconChoose">
            <div class="card-header">
                <button type="submit" class="btn btn-default" v-on:click="toMain">Back</button>
            </div>
            <div class="card-body">
                <swatches-picker v-if="isColorChoose" class="form-page-color-picker" :value="form.color"
                                 @input="updateColorValue"/>
                <swatches-picker v-if="isTextColorChoose" class="form-page-color-picker" :value="form.textColor"
                                 @input="updateTextColorValue"/>
                <icon-choose v-if="isIconChoose" :value="form.icon" @input="updateIconValue"/>
            </div>
        </div>
        <div class="card form-page-card" v-if="isMain">
            <div class="card-header">
                <button type="submit" class="btn btn-default" v-on:click="backCallback">Back</button>
                <div v-if="isEditMode" class="dropdown d-inline-block">
                    <button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="far fa-trash-alt"></i>
                            Delete Link
                        </button>
                    </div>
                </div>
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
                    <button type="button" class="btn btn-outline-dark" v-on:click="toIconChoose()">
                        <i class="far fa-image"></i>
                        Choose Icon
                    </button>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-dark" v-on:click="toColorChoose()">
                        <i class="fas fa-fill-drip"></i>
                        Choose Background Color
                    </button>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-dark" v-on:click="toTextColorChoose()">
                        <i class="fas fa-align-left"></i>
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
            <div :style="backgroundColor">
                <div :class="getColClass(form)">
                    <styled-button :link="form" :style-id="buttonStyle.id"></styled-button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Link</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure that you want to delete this link?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" v-on:click="deleteLink">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Swatches} from "vue-color";
    import iconChoose from "./iconChoose";
    import styledButton from "../../../components/styledButton";
    import $ from "jquery";

    const MODE_MAIN = 'main';
    const MODE_COLOR_CHOOSE = 'color_choose';
    const MODE_TEXT_COLOR_CHOOSE = 'text_color_choose';
    const MODE_ICON_CHOOSE = 'icon_choose';

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
            },
            buttonStyle: {
                type: Object
            },
            backgroundStyle: {
                type: Object
            },
        },
        beforeMount() {
            if (this.editLink.hasOwnProperty('id')) {
                this.form = {
                    id: this.editLink.id,
                    url: this.editLink.settings.url || '',
                    title: this.editLink.title || '',
                    size: this.editLink.settings.size || 12,
                    color: this.editLink.settings.backgroundColor || '#007bff',
                    textColor: this.editLink.settings.textColor || '#ffffff',
                    icon: this.editLink.settings.icon || ''
                };
            } else {
                this.form = {
                    id: null,
                    url: '',
                    title: '',
                    size: 12,
                    color: '#007bff',
                    textColor: '#ffffff',
                    icon: ''
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
            backgroundColor() {
                let style = this.backgroundStyle && this.backgroundStyle.hasOwnProperty('color') ? this.backgroundStyle.color : 'background-color: #f4f6f9';
                return style + ' width: 100%; padding: 40px;';
            },
            isEditMode() {
                return this.form.hasOwnProperty('id') && this.form.id !== null;
            },
            isMain() {
                return this.mode === MODE_MAIN
            },
            isColorChoose() {
                return this.mode === MODE_COLOR_CHOOSE
            },
            isTextColorChoose() {
                return this.mode === MODE_TEXT_COLOR_CHOOSE
            },
            isIconChoose() {
                return this.mode === MODE_ICON_CHOOSE
            },
            exampleButtonTitle() {
                let icon = this.form.icon.length ? `<i class="${this.form.icon}"></i>` : '';
                let text = this.form.title || (icon.length ? '' : 'Example title');
                return `${icon} ${text}`;
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
                    icon: this.form.icon,
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
            deleteLink() {
                const url = '/api/page/' + this.pageId + '/deleteLink/' + this.form.id;
                this.$http.get(url).then(response => {
                    $('.modal-backdrop').remove();
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
            updateIconValue(icon) {
                this.form.icon = icon;
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
            toIconChoose() {
                this.mode = MODE_ICON_CHOOSE;
            }
        },
        components: {
            'swatches-picker': Swatches,
            'icon-choose': iconChoose,
            'styled-button': styledButton
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