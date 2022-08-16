import Modal, {IInternalModalAttrs} from 'flarum/common/components/Modal';
import FileListState from '../states/FileListState';
import FileSelectList from './FileSelectList';
import File from '../../common/models/File';

interface FileSelectionModalAttrs extends IInternalModalAttrs {
    onselect: (file: File) => void
}

export default class FileSelectionModal extends Modal<FileSelectionModalAttrs> {
    listState!: FileListState

    oninit(vnode: any) {
        super.oninit(vnode);

        this.listState = new FileListState();
        this.listState.refresh();
    }

    className() {
        return 'FlamarktLibrarySelectionModal';
    }

    title() {
        return app.translator.trans('flamarkt-library.backoffice.select.title');
    }

    content() {
        return m('.Modal-body', m(FileSelectList, {
            state: this.listState,
            onselect: (file: File) => {
                this.attrs.onselect(file);
                this.hide();
            },
        }));
    }
}
