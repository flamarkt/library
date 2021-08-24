import {extend} from 'flarum/common/extend';
import ItemList from 'flarum/common/utils/ItemList';
import ProductSummary from 'flamarkt/core/mithril2html/pages/ProductSummary';
import Image from 'flamarkt/library/forum/components/Image'; // Use our own namespace so extensions in forum namespace can apply

app.initializers.add('flamarkt-library-mithril2html', () => {
    extend(ProductSummary.prototype, 'sections', function (this: ProductSummary, sections: ItemList) {
        const file = this.product.thumbnail();

        if (!file) {
            return;
        }

        sections.add('thumbnail', m('div', m(Image, {
            file,
        })), 50);
    })
});
