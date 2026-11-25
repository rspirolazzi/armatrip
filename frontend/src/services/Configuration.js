import Base from './Base'

class ConfigurationService extends Base {
    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    config(params) {
        try {
            return this.get('config', params)
                .then(resp=>{
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new ConfigurationService()