import Base from './Base'

class CoreService extends Base {

    postLogToServer(params) {
        try {
            return this.post(`log/save`,params)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new CoreService()