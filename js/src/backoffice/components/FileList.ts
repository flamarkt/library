import AbstractList from 'flamarkt/core/backoffice/components/AbstractList';
import LinkButton from 'flarum/common/components/LinkButton';
import Button from 'flarum/common/components/Button';
import File from '../../common/models/File';

export default class FileList extends AbstractList {
    head() {
        const columns = super.head();

        columns.add('thumbnail', m('th', 'Thumbnail'));//TODO
        columns.add('title', m('th', 'Title'));//TODO

        return columns;
    }

    columns(file: File) {
        const columns = super.columns(file);

        columns.add('thumbnail', m('td', m('img', {
            src: file.conversionUrl('150x150'),
            alt: 'Thumbnail',
        })));
        columns.add('title', m('td', file.title()));

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
