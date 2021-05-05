import AbstractListState from 'flamarkt/core/common/states/AbstractListState';

export default class FileListState extends AbstractListState {
    type() {
        return 'flamarkt/files';
    }
}
