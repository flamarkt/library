import Model from 'flarum/common/Model';

export default class File extends Model {
    conversions = Model.attribute('conversions');
    title = Model.attribute('title');
    description = Model.attribute('description');

    conversionUrl(name) {
        if (this.conversions()[name]) {
            return this.conversions()[name];
        }

        // Default to first conversion TODO: add logic
        return this.conversions()[Object.keys(this.conversions())[0]];
    }

    apiEndpoint() {
        return '/flamarkt/files' + (this.exists ? '/' + this.data.id : '');
    }
}
