import Page from 'flarum/common/components/Page';
import FileListState from '../states/FileListState';
import FileList from '../components/FileList';

/* global m */

export default class FileIndexPage extends Page {
    state!: FileListState;
    uploading: boolean = false;

    oninit() {
        this.state = new FileListState();
        this.state.refresh();
    }

    view() {
        return m('.ProductIndexPage', [
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
                        this.state.add(app.store.pushPayload(result));
                        m.redraw();
                    }).catch(err => {
                        this.uploading = false;
                        m.redraw();
                        throw err;
                    });
                }
            }),
            m(FileList, {
                state: this.state,
            }),
        ]);
    }
}
