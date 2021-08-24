import {extend} from 'flarum/common/extend';
import Product from 'flamarkt/core/common/models/Product';
import ProductListItem from 'flamarkt/core/forum/components/ProductListItem';
import ProductShowLayout from 'flamarkt/core/forum/layouts/ProductShowLayout';
import OrderTable from 'flamarkt/core/forum/components/OrderTable';
import OrderTableGroupFoot from 'flamarkt/core/forum/components/OrderTableGroupFoot';
import OrderTableGroupHead from 'flamarkt/core/forum/components/OrderTableGroupHead';
import OrderTableRow from 'flamarkt/core/forum/components/OrderTableRow';
import Model from 'flarum/common/Model';
import Link from 'flarum/common/components/Link';
import File from '../common/models/File';
import ItemList from 'flarum/common/utils/ItemList';
import Image from './components/Image';
import {common} from '../common/compat';
import {forum} from './compat';

export {
    common,
    forum,
}

app.initializers.add('flamarkt-library', () => {
    app.store.models['flamarkt-files'] = File;

    Product.prototype.thumbnail = Model.hasOne('thumbnail');

    extend(ProductListItem.prototype, 'items', function (items: ItemList) {
        const file = this.attrs.product.thumbnail();

        items.add('thumbnail', Image.component({
            className: 'ProductListItem--thumbnail',
            file,
        }), 30);
    });

    extend(ProductShowLayout.prototype, 'gallerySection', function (items: ItemList, product) {
        const file = product.thumbnail();

        items.add('thumbnail', Image.component({
            file,
        }));
    });

    extend(OrderTable.prototype, ['head', 'foot'], function (items: ItemList) {
        items.add('thumbnail', m('th'), 31);
    });

    extend(OrderTableGroupFoot.prototype, 'columns', function (items: ItemList) {
        items.add('thumbnail', m('th'), 31);
    });

    extend(OrderTableGroupHead.prototype, 'columns', function (items: ItemList) {
        items.add('thumbnail', m('th'), 31);
    });

    extend(OrderTableRow.prototype, 'columns', function (this: OrderTableRow, items: ItemList) {
        const product = this.attrs.line.product();

        items.add('thumbnail', m('td', m(Link, {
            href: app.route.product(product),
        }, Image.component({
            file: product ? product.thumbnail() : null,
        }))), 31);
    });
});
