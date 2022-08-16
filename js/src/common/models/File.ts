import Model from 'flarum/common/Model';

export default class File extends Model {
    conversions = Model.attribute<{ [key: string]: string }>('conversions');
    title = Model.attribute<string>('title');
    description = Model.attribute<string>('description');

    conversionUrl(name: string) {
        if (this.conversions()[name]) {
            return this.conversions()[name];
        }

        // Default to first conversion TODO: add logic
        return this.conversions()[Object.keys(this.conversions())[0]];
    }

    apiEndpoint() {
        return '/flamarkt/files' + (this.exists ? '/' + (this.data as any).id : '');
    }
}
