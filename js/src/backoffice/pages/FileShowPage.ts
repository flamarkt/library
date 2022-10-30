import app from 'flamarkt/backoffice/backoffice/app';
import AbstractShowPage from 'flamarkt/backoffice/common/pages/AbstractShowPage';
import SubmitButton from 'flamarkt/backoffice/backoffice/components/SubmitButton';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
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
                m('label', app.translator.trans('flamarkt-library.backoffice.files.field.title')),
                m('input.FormControl', {
                    type: 'text',
                    value: this.title,
                    oninput: (event: InputEvent) => {
                        this.title = (event.target as HTMLInputElement).value;
                        this.dirty = true;
                    },
                }),
            ]),
            m('.Form-group', [
                m('label', app.translator.trans('flamarkt-library.backoffice.files.field.description')),
                m('textarea.FormControl', {
                    value: this.description,
                    oninput: (event: InputEvent) => {
                        this.description = (event.target as HTMLInputElement).value;
                        this.dirty = true;
                    },
                }),
            ]),
            m('.Form-group', [
                SubmitButton.component({
                    loading: this.saving,
                    dirty: this.dirty,
                    exists: this.file.exists,
                }),
            ]),
        ]));
    }

    data() {
        return {
            title: this.title,
            description: this.description,
        };
    }

    onsubmit(event: Event) {
        event.preventDefault();

        this.saving = true;

        // @ts-ignore
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
