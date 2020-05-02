<template>
    <section class="content">
        <br>
        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12">
                <preview v-if="isMain" :page-id="id" v-model="links" :add-new-link-callback="toForm"
                         :edit-link-callback="toForm">
                </preview>

                <formPage v-if="isForm" :editLink="linkEdit" :page-id="id" :back-callback="toMain">
                </formPage>
            </div>
        </div>
    </section>
</template>

<script>
    import preview from "./editPage/preview";
    import formPage from "./editPage/formPage";

    const MODE_MAIN = 'main';
    const MODE_FORM = 'form';

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
                links: [],
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
            isForm() {
                return this.mode === MODE_FORM
            }
        },
        methods: {
            getLayout() {
                this.$http.get('/api/page/' + this.id + '/getLayout').then(response => {
                    this.links = response.data;
                });
            },
            toForm(link = {}) {
                this.linkEdit = link;
                this.mode = MODE_FORM;
            },
            toMain() {
                this.getLayout();
                this.mode = MODE_MAIN;
            },
        },
        components: {
            preview,
            formPage
        }
    }
</script>

<style scoped></style>