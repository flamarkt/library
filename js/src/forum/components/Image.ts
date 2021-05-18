import Component, {ComponentAttrs} from 'flarum/common/Component';
import File from '../../common/models/File';

interface ImageAttrs extends ComponentAttrs {
    file?: File
    size?: string
}

export default class Image extends Component<ImageAttrs> {
    view() {
        const {file} = this.attrs;

        if (!file) {
            return m('div', 'No image');
        }

        return m('img', {
            src: file.conversionUrl(this.attrs.size || '150x150'),
            alt: file.title(),
        });
    }
}
