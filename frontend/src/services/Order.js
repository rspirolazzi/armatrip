import Base from './Base'

class OrderService extends Base {
    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    checkout(params) {
        try {
            return this.postWithJWT('checkout', params)
                .then(resp=>{
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    payment(params) {
        try {
            return this.postWithJWT('payment', params)
                .then(resp=>{
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    getOrderShow(code) {
        try {
            return this.get(`order/show/${code}`)
                .then(resp=>{
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new OrderService()