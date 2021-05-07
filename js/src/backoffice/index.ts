import File from '../common/models/File';
import BackofficeNav from 'flamarkt/core/backoffice/components/BackofficeNav';
import ProductList from 'flamarkt/core/backoffice/components/ProductList';
import ActiveLinkButton from 'flamarkt/core/common/components/ActiveLinkButton';
import {extend} from 'flarum/common/extend';
import FileIndexPage from './pages/FileIndexPage';
import FileShowPage from './pages/FileShowPage';

app.initializers.add('flamarkt-library', () => {
    app.store.models['flamarkt-files'] = File;

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

    extend(ProductList.prototype, 'columns', function (columns) {
        columns.add('thumbnail', m('td', 'test'), 30);
    });
});
