import Base from './Base'

class PackageService extends Base {

    getPackages(store) {
        try {
            return this.get(`packages/${store}`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    getPackage(code) {
        try {
            return this.get(`package/${code}`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
    getPackageDetail(params) {
        try {
            return this.postWithJWT(`package/detail`,params)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new PackageService()