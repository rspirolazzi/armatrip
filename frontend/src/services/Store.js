import Base from './Base'

class StoreService extends Base {

    getStores() {
        try {
            return this.get('stores')
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new StoreService()