import AbstractList from 'flamarkt/backoffice/backoffice/components/AbstractList';
import LinkButton from 'flarum/common/components/LinkButton';
import Button from 'flarum/common/components/Button';
import File from '../../common/models/File';

export default class FileList extends AbstractList<File> {
    head() {
        const columns = super.head();

        columns.add('thumbnail', m('th', app.translator.trans('flamarkt-library.backoffice.files.head.thumbnail')), 20);
        columns.add('title', m('th', app.translator.trans('flamarkt-library.backoffice.files.head.title')), 10);

        return columns;
    }

    columns(file: File) {
        const columns = super.columns(file);

        columns.add('thumbnail', m('td', m('img', {
            src: file.conversionUrl('150x150'),
            alt: file.title(),
        })), 20);
        columns.add('title', m('td', file.title()), 10);

        return columns;
    }

    actions(file: File) {
        const actions = super.actions(file);

        actions.add('edit', LinkButton.component({
            className: 'Button Button--icon',
            icon: 'fas fa-pen',
            href: app.route('files.show', {
                id: file.id(),
            }),
        }));

        actions.add('hide', Button.component({
            className: 'Button Button--icon',
            icon: 'fas fa-times',
        }));

        return actions;
    }
}
