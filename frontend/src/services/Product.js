import Base from './Base'

class ProductService extends Base {

    getProducts() {
        try {
            return this.get('products')
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    getProduct(code) {
        try {
            return this.get(`products/${code}`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new ProductService()