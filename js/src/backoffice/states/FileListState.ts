import AbstractListState from 'flamarkt/backoffice/common/states/AbstractListState';
import File from '../../common/models/File';

export default class FileListState extends AbstractListState<File> {
    type() {
        return 'flamarkt/files';
    }
}
