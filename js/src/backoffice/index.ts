import File from '../common/models/File';
import BackofficeNav from 'flamarkt/backoffice/backoffice/components/BackofficeNav';
import ActiveLinkButton from 'flamarkt/backoffice/common/components/ActiveLinkButton';
import ProductList from 'flamarkt/core/backoffice/components/ProductList';
import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';
import Product from 'flamarkt/core/common/models/Product';
import {extend} from 'flarum/common/extend';
import Button from 'flarum/common/components/Button';
import FileIndexPage from './pages/FileIndexPage';
import FileShowPage from './pages/FileShowPage';
import FileSelectionModal from './components/FileSelectionModal';
import Model from 'flarum/common/Model';

app.initializers.add('flamarkt-library', () => {
    app.store.models['flamarkt-files'] = File;

    Product.prototype.thumbnail = Model.hasOne('thumbnail');

    app.routes['files.index'] = {
        path: '/files',
        component: FileIndexPage,
    };
    app.routes['files.show'] = {
        path: '/files/:id',
        component: FileShowPage,
    };

    extend(BackofficeNav.prototype, 'items', function (items) {
        items.add('library', ActiveLinkButton.component({
            href: app.route('files.index'),
            icon: 'fas fa-file',
            activeRoutes: [
                'files.*',
            ],
        }, 'Library'));
    });

    extend(ProductList.prototype, 'head', function (columns) {
        columns.add('thumbnail', m('th', 'Thumbnail'), 30);
    });

    extend(ProductList.prototype, 'columns', function (columns, product) {
        const file = product.thumbnail();

        columns.add('thumbnail', m('td', file ? m('img', {
            src: file.conversionUrl('150x150'),
            alt: product.title(),
        }) : null), 30);
    });

    extend(ProductShowPage.prototype, 'oninit', function () {
        this.thumbnail = null;
    });

    extend(ProductShowPage.prototype, 'show', function (returnValue, product) {
        this.thumbnail = product.thumbnail() || null;
    });

    extend(ProductShowPage.prototype, 'fields', function (fields) {
        fields.add('thumbnail', m('.Form-group', [
            m('label', 'Thumbnail'),
            this.thumbnail ? [
                m('img', {
                    src: this.thumbnail.conversionUrl('150x150'),
                }),
                Button.component({
                    className: 'Button',
                    icon: 'fas fa-times',
                    onclick: () => {
                        this.thumbnail = null;
                        this.dirty = true;
                    },
                }, 'Remove'),
            ] : null,
            Button.component({
                className: 'Button',
                icon: 'fas fa-file',
                onclick: () => {
                    app.modal.show(FileSelectionModal, {
                        onselect: file => {
                            this.thumbnail = file;
                            this.dirty = true;
                        },
                    });
                },
            }, 'Choose'),
        ]));
    });

    extend(ProductShowPage.prototype, 'data', function (data) {
        data.relationships = data.relationships || {};
        data.relationships.thumbnail = this.thumbnail;
    });
});
