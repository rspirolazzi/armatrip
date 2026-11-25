import Base from './Base'

class ProvinceService extends Base {

    getProvinces(store) {
        try {
            return this.get(`provinces/${store}`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

}

export default new ProvinceService()