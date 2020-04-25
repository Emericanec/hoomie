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
                    <button v-for="link in links" class="btn btn-primary btn-block btn-lg">{{link.title}}</button>
                </div>
            </div>
        </div>
        <div v-if="isAddNewLink">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="submit" class="btn btn-default" v-on:click="toMain()">Back</button>
                            <button type="submit" class="btn btn-info float-right" v-on:click="addNewLink()">Save</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" placeholder="Enter title" v-model="form.title">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
`;

const MODE_MAIN = 'main';
const MODE_ADD_NEW_LINK = 'add_new_link';

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
            form: {}
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
    },
    methods: {
        addNewLink() {
            let payload = {
                title: this.form.title
            };
            console.log(payload);
            this.$http.post('/api/page/' + this.id + '/addLink', payload).then(response => {
                this.toMain();
            });
        },
        getLayout() {
            this.$http.get('/api/page/' + this.id + '/getLayout').then(response => {
                this.links = response.data;
            });
        },
        toAddNewLink() {
            this.form = {
                title: ''
            };
            this.mode = MODE_ADD_NEW_LINK;
        },
        toMain() {
            this.getLayout();
            this.mode = MODE_MAIN;
        }
    }
};