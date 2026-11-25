import Base from './Base'

class CouponService extends Base {
    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    check(params) {
        try {
            return this.postWithJWT('coupons/check', params)
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
    apply(params) {
        try {
            return this.postWithJWT('coupons/apply', params)
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
    remove(params) {
        try {
            return this.postWithJWT('coupons/remove', params)
                .then(resp=>{
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new CouponService()