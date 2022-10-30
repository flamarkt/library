import app from 'flamarkt/backoffice/backoffice/app';
import SearchInput from 'flamarkt/backoffice/backoffice/components/SearchInput';
import Modal, {IInternalModalAttrs} from 'flarum/common/components/Modal';
import extractText from 'flarum/common/utils/extractText';
import ItemList from 'flarum/common/utils/ItemList';
import FileListState from '../states/FileListState';
import FileSelectList from './FileSelectList';
import File from '../../common/models/File';

interface FileSelectionModalAttrs extends IInternalModalAttrs {
    onselect: (file: File) => void
}

export default class FileSelectionModal extends Modal<FileSelectionModalAttrs> {
    list!: FileListState

    oninit(vnode: any) {
        super.oninit(vnode);

        this.list = new FileListState();
        this.list.refresh();
    }

    className() {
        return 'FlamarktLibrarySelectionModal';
    }

    title() {
        return app.translator.trans('flamarkt-library.backoffice.select.title');
    }

    filters() {
        const items = new ItemList();

        items.add('search', m(SearchInput, {
            initialValue: '',
            onchange: (value: string) => {
                this.list.params.q = value;
                this.list.refresh();
            },
            placeholder: extractText(app.translator.trans('flamarkt-library.backoffice.files.searchPlaceholder')),
        }), 50);

        return items;
    }

    content() {
        return m('.Modal-body', [
            m('.BackofficeListFilters', this.filters().toArray()),
            m(FileSelectList, {
                state: this.list,
                onselect: (file: File) => {
                    this.attrs.onselect(file);
                    this.hide();
                },
            }),
        ]);
    }
}
