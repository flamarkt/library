import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';
import File from './src/common/models/File';

declare module 'flamarkt/core/backoffice/pages/ProductShowPage' {
    export default interface ProductShowPage {
        thumbnail: File | null
    }
}

declare module 'flamarkt/core/common/models/Product' {
    export default interface Product {
        thumbnail: () => File | false
    }
}
