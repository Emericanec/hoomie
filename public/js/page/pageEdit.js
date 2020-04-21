let self;

class PageEdit {
    constructor(id, selector, modalSelector) {
        this.id = id;
        this.selector = selector;
        this.modalSelector = modalSelector;
        this.endpoint = '/api/page';
        self = this;
    }

    getUrl(path) {
        return this.endpoint + '/' + this.id + '/' + path;
    }

    addButtonModal() {
        let modal = new AddButtonModal(this.modalSelector, this.addNewButton);
        modal.render();
    }

    addNewButton() {
        const url = self.getUrl('addLink');
        const payload = {
            'title': $(self.modalSelector).find('input[name=title]').val()
        };
        $.post(url, payload, response => {
            self.render();
            $(self.modalSelector).modal('hide');
        });

    }

    render() {
        const url = this.getUrl('getLayout');
        $.get(url, null, response => {
            this.renderLayout(response);
        });
    }

    renderLayout(data) {
        data = JSON.parse(data);
        $(this.selector).html('');
        data.forEach((value) => {
            $(this.selector).append(`
                <button class="btn btn-primary btn-block">${value.title}</button>
            `);
        });
    }
}

class AddButtonModal {
    constructor(selector, addButtonCallback) {
        this.addButtonCallback = addButtonCallback;
        this.$modal = $(selector);
        this.$title = this.$modal.find('.modal-title');
        this.$body = this.$modal.find('.modal-body');
        this.$footer = this.$modal.find('.modal-footer');
    }

    getTitle() {
        return 'Add new link';
    }

    getBody() {
        return `
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" placeholder="Enter title" name="title">
            </div>
        `;
    }

    getFooter() {
        return `
            <button type="button" class="btn btn-info add-button-modal">Add link</button>
            <button type="button" class="btn" data-dismiss="modal">Close</button>
        `;
    }

    render() {
        this.$title.html(this.getTitle());
        this.$body.html(this.getBody());
        this.$footer.html(this.getFooter());
        this.$modal.modal('show');

        this.$modal.find('button.add-button-modal').on('click', this.addButtonCallback);
    }
}