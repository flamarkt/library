import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';
import File from './src/common/models/File';

declare module 'flamarkt/core/backoffice/pages/ProductShowPage' {
    export default interface ProductShowPage {
        thumbnail: File | null
    }
}
