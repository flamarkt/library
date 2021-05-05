import {extend} from 'flarum/common/extend';
import Product from 'flamarkt/core/common/models/Product';
import Model from 'flarum/common/Model';
import File from '../common/models/File';

app.initializers.add('flamarkt-library', () => {
    app.store.models['flamarkt-files'] = File;

    Product.prototype.files = Model.hasMany('files');
});
