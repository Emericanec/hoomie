<template>
    <div>
        <button class="btn btn-outline-dark btn-block btn-lg preview-add-new-link-button"
                v-on:click="addNewLinkCallback()">Add New Link
        </button>
        <div class="preview-edit-page" :style="'background-color:' + backgroundStyle.color">
            <div class="text-center">
                <img style="width: 150px;" class="img-circle elevation-2"
                     src="https://scontent-ams4-1.cdninstagram.com/v/t51.2885-19/s150x150/54247715_2195250907184796_8702997699101720576_n.jpg?_nc_ht=scontent-ams4-1.cdninstagram.com&amp;_nc_ohc=MEyEmqsvTzIAX_wg9iv&amp;oh=152b9cd35d3d5283e03806c14fe4e2d3&amp;oe=5EC9B20C">
                <br><br>
                <div class="text-dark">
                    @took_took_kto_tam
                </div>
            </div>
            <br>
            <draggable v-model="linkListDraggable" tag="div" handle=".handle" class="row">
                <div v-for="link in linkListDraggable" :class="getLinkColClass(link)">
                    <div class="preview-drag-block">
                        <i class="nav-icon fas fa-arrows-alt-v handle preview-drag-icon"></i>
                    </div>
                    <button :class="buttonClasses"
                            :style="{marginTop: '8px', backgroundColor: link.settings.backgroundColor, color: link.settings.textColor}"
                            v-on:click="editLinkCallback(link)" v-html="getLinkText(link)"></button>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import draggable from "vuedraggable";

    export default {
        name: "preview.vue",
        props: {
            pageId: {
                type: String
            },
            buttonStyle: {
                type: Object
            },
            backgroundStyle: {
                type: Object
            },
            value: {
                type: Array
            },
            addNewLinkCallback: {
                type: Function
            },
            editLinkCallback: {
                type: Function
            }
        },
        computed: {
            backgroundColor() {
                return this.backgroundColor.hasOwnProperty('color') ? this.backgroundColor.color : '#f4f6f9';
            },
            buttonClasses() {
                let classes = this.buttonStyle.hasOwnProperty('classes') ? this.buttonStyle.classes : '';
                return 'btn btn-block btn-lg ' + classes;
            },
            linkListDraggable: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit('input', value);
                    this.saveSort(value);
                }
            }
        },
        methods: {
            getLinkColClass(link) {
                let size = link.size || link.settings.size || 12;
                size = parseInt(size);
                return 'col-' + size;
            },
            saveSort(newValue) {
                let url = '/api/page/' + this.pageId + '/saveSort';
                let payload = newValue.map(link => link.id);
                console.log(payload);
                this.$http.post(url, payload).then(response => {
                    // todo
                });
            },
            getLinkText(link) {
                let icon = '';
                if (link.settings.hasOwnProperty('icon') && link.settings.icon.length) {
                    icon = `<i class="${link.settings.icon}"></i>`;
                }
                return `${icon} ${link.title}`;
            }
        },
        components: {
            draggable
        }
    }
</script>

<style scoped>
    .preview-drag-block {
        position: relative;
        width: 0;
        height: 0;
        left: calc(100% + 2px);
    }

    .preview-drag-icon {
        position: absolute;
        font-size: 1.5rem;
        top: 20px;
    }

    .preview-edit-page {
        margin: 0 auto;
        width: 375px;
        max-width: 375px;
        border: 10px solid;
        border-radius: 40px;
        padding: 20px;
        height: 700px;
        max-height: 700px;
        overflow-y: scroll;
    }

    .preview-add-new-link-button {
        width: 375px;
        max-width: 375px;
        margin: 0 auto 10px;
    }
</style>