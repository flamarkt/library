import {Vnode} from 'mithril';
import app from 'flamarkt/backoffice/backoffice/app';
import SearchInput from 'flamarkt/backoffice/backoffice/components/SearchInput';
import Page from 'flarum/common/components/Page';
import extractText from 'flarum/common/utils/extractText';
import ItemList from 'flarum/common/utils/ItemList';
import {ApiPayloadSingle} from 'flarum/common/Store';
import FileListState from '../states/FileListState';
import FileList from '../components/FileList';

export default class FileIndexPage extends Page {
    list!: FileListState;
    uploading: boolean = false;

    oninit(vnode: Vnode) {
        super.oninit(vnode);

        this.list = new FileListState();
        this.list.refresh();
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

        items.add('separator', m('.BackofficeListFilters--separator'), -10);

        items.add('upload', m('input', {
            type: 'file',
            onchange: (event: InputEvent) => {
                const body = new FormData();

                body.append('file', (event.target as HTMLInputElement).files![0]);

                this.uploading = true;
                m.redraw();

                app.request<ApiPayloadSingle>({
                    method: 'POST',
                    url: app.forum.attribute('apiUrl') + '/flamarkt/files',
                    serialize: (raw: any) => raw,
                    body,
                }).then(result => {
                    this.uploading = false;
                    this.list.add(app.store.pushPayload(result));
                    m.redraw();
                }).catch(err => {
                    this.uploading = false;
                    m.redraw();
                    throw err;
                });
            }
        }), -100);

        return items;
    }

    view() {
        return m('.FileIndexPage', m('.container', [
            m('.BackofficeListFilters', this.filters().toArray()),
            m(FileList, {
                list: this.list,
            }),
        ]));
    }
}
