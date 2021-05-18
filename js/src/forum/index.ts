import {extend} from 'flarum/common/extend';
import Product from 'flamarkt/core/common/models/Product';
import ProductShowLayout from 'flamarkt/core/forum/layouts/ProductShowLayout';
import Model from 'flarum/common/Model';
import File from '../common/models/File';
import ItemList from 'flarum/common/utils/ItemList';
import Image from './components/Image';

app.initializers.add('flamarkt-library', () => {
    app.store.models['flamarkt-files'] = File;

    Product.prototype.thumbnail = Model.hasOne('thumbnail');

    extend(ProductShowLayout.prototype, 'gallerySection', function (items: ItemList, product) {
        const file = product.thumbnail();

        items.add('thumbnail', Image.component({
            file,
        }));
    });
});
