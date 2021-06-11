import Component, {ComponentAttrs} from 'flarum/common/Component';
import File from '../../common/models/File';

interface ImageAttrs extends ComponentAttrs {
    file?: File
    size?: string
    className?: string
}

export default class Image extends Component<ImageAttrs> {
    view() {
        const {file, size, className} = this.attrs;

        if (!file) {
            return m('div', {
                className,
            }, 'No image');
        }

        return m('img', {
            src: file.conversionUrl(size || '150x150'),
            alt: file.title(),
            className,
        });
    }
}
