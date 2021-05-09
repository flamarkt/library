import FileList from './FileList';
import File from '../../common/models/File';
import Button from 'flarum/common/components/Button';
import ItemList from 'flarum/common/utils/ItemList';

export default class FileSelectList extends FileList {
    actions(file: File) {
        const actions = new ItemList();

        actions.add('select', Button.component({
            className: 'Button',
            icon: 'fas fa-check',
            onclick: () => {
                this.attrs.onselect(file);
            },
        }, 'Select'));

        return actions;
    }
}
