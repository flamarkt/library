import Modal from 'flarum/common/components/Modal';
import FileListState from '../states/FileListState';
import FileSelectList from './FileSelectList';

export default class FileSelectionModal extends Modal {
    oninit(vnode) {
        super.oninit(vnode);

        this.state = new FileListState();
        this.state.refresh();
    }

    title() {
        return 'Select a file';
    }

    content() {
        return m('.Modal-body', m(FileSelectList, {
            state: this.state,
            onselect: file => {
                this.attrs.onselect(file);
                this.hide();
            },
        }));
    }
}
