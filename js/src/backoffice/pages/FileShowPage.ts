import AbstractShowPage from 'flamarkt/core/common/pages/AbstractShowPage';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Button from 'flarum/common/components/Button';
import File from '../../common/models/File';

export default class FileShowPage extends AbstractShowPage {
    file: File | null = null;
    dirty: boolean = false;
    saving: boolean = false;
    title: string = '';
    description: string = '';

    findType() {
        return 'flamarkt/files';
    }

    show(file: File) {
        this.file = file;
        this.title = file.title() || '';
        this.description = file.description() || '';

        app.setTitle(file.title());
        app.setTitleCount(0);
    }

    view() {
        if (!this.file) {
            return LoadingIndicator.component();
        }

        return m('form.FileShowPage', {
            onsubmit: this.onsubmit.bind(this),
        }, m('.container', [
            m('.Form-group', [
                m('label', 'Title'),
                m('input.FormControl', {
                    type: 'text',
                    value: this.title,
                    oninput: event => {
                        this.title = event.target.value;
                        this.dirty = true;
                    },
                }),
            ]),
            m('.Form-group', [
                m('label', 'Description'),
                m('textarea.FormControl', {
                    value: this.description,
                    oninput: event => {
                        this.description = event.target.value;
                        this.dirty = true;
                    },
                }),
            ]),
            m('.Form-group', [
                Button.component({
                    type: 'submit',
                    className: 'Button Button--primary',
                    loading: this.saving,
                    disabled: !this.dirty,
                }, 'Save'),
            ]),
        ]));
    }

    data() {
        return {
            title: this.title,
            description: this.description,
        };
    }

    onsubmit(event) {
        event.preventDefault();

        this.saving = true;

        this.file.save(this.data()).then(file => {
            this.file = file;

            this.saving = false;
            this.dirty = false;
            m.redraw();
        }).catch(error => {
            this.saving = false;
            m.redraw();
        });
    }
}
