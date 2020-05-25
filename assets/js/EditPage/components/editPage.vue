<template>
    <section class="content">
        <br>
        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12">
                <preview v-if="isMain"
                         :page-id="id"
                         :button-style="buttonStyle"
                         :background-style="backgroundStyle"
                         v-model="nodes"
                         :add-new-block-callback="toAddBlock"
                         :edit-link-callback="toLinkForm">
                </preview>

                <add-block v-if="isAddBlock"
                           :back-callback="toMain"
                           :to-link-form-callback="toLinkForm"
                           :to-text-form-callback="toTextForm">
                </add-block>

                <add-text-form v-if="isTextForm"
                               :back-callback="toAddBlock">
                </add-text-form>

                <add-link-form v-if="isLinkForm"
                               :editLink="linkEdit"
                               :page-id="id"
                               :back-callback="backFromAddLinkForm"
                               :button-style="buttonStyle"
                               :background-style="backgroundStyle">
                </add-link-form>
            </div>
        </div>
    </section>
</template>

<script>
    import preview from "./editPage/preview";
    import formPage from "./editPage/formPage";
    import addBlock from "./editPage/addBlock";
    import addText from "./editPage/addText";

    const MODE_MAIN = 'main';
    const MODE_LINK_FORM = 'link_form';
    const MODE_TEXT_FORM = 'text_form';
    const MODE_ADD_BLOCK = 'add_block';

    export default {
        name: "editPage",
        props: {
            id: {
                type: String
            }
        },
        data() {
            return {
                mode: MODE_MAIN,
                nodes: [],
                buttonStyle: null,
                backgroundStyle: null,
                linkEdit: null
            }
        },
        created() {
            this.getLayout()
        },
        computed: {
            isMain() {
                return this.mode === MODE_MAIN
            },
            isLinkForm() {
                return this.mode === MODE_LINK_FORM
            },
            isTextForm() {
                return this.mode === MODE_TEXT_FORM
            },
            isAddBlock() {
                return this.mode === MODE_ADD_BLOCK
            }
        },
        methods: {
            getLayout() {
                this.$http.get('/api/page/' + this.id + '/getLayout').then(response => {
                    this.buttonStyle = response.data.button_style;
                    this.backgroundStyle = response.data.background_style;
                    this.nodes = response.data.nodes.map((currentValue) => {
                        currentValue.jsonSettings = JSON.parse(currentValue.jsonSettings);
                        return currentValue;
                    });
                });
            },
            toLinkForm(event, link = {}) {
                this.linkEdit = link;
                this.mode = MODE_LINK_FORM;
            },
            toTextForm(event) {
                this.mode = MODE_TEXT_FORM;
            },
            toAddBlock(link = {}) {
                this.mode = MODE_ADD_BLOCK;
            },
            toMain() {
                this.getLayout();
                this.mode = MODE_MAIN;
            },
            backFromAddLinkForm() {
                console.log(this.linkEdit);
                Object.keys(this.linkEdit).length === 0 ? this.toAddBlock() : this.toMain();
            }
        },
        components: {
            preview,
            'add-link-form': formPage,
            'add-text-form': addText,
            'add-block': addBlock
        }
    }
</script>

<style scoped></style>