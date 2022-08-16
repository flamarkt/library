import Page from 'flarum/common/components/Page';
import FileListState from '../states/FileListState';
import FileList from '../components/FileList';

export default class FileIndexPage extends Page {
    listState!: FileListState;
    uploading: boolean = false;

    oninit(vnode) {
        super.oninit(vnode);

        this.listState = new FileListState();
        this.listState.refresh();
    }

    view() {
        return m('.FileIndexPage', m('.container', [
            m('input', {
                type: 'file',
                onchange: event => {
                    const body = new FormData();

                    body.append('file', event.target.files[0]);

                    this.uploading = true;
                    m.redraw();

                    app.request({
                        method: 'POST',
                        url: app.forum.attribute('apiUrl') + '/flamarkt/files',
                        serialize: raw => raw,
                        body,
                    }).then(result => {
                        this.uploading = false;
                        this.listState.add(app.store.pushPayload(result));
                        m.redraw();
                    }).catch(err => {
                        this.uploading = false;
                        m.redraw();
                        throw err;
                    });
                }
            }),
            m(FileList, {
                state: this.listState,
            }),
        ]));
    }
}
