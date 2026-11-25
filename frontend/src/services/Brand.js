import Base from './Base'

class BrandService extends Base {

    getBrands() {
        try {
            return this.get(`brands`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

}

export default new BrandService()