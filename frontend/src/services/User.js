import Base, {delJWT, setJWT} from './Base'

class UserService extends Base {
    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    login({email, password}) {
        try {
            const thiz = this
            return this.post('login', {email, password})
                .then(resp => {
                    setJWT(resp.data.token)
                    return resp
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    register(data) {
        try {
            const thiz = this
            return this.post('register', data)
                .then(resp => {
                    if (resp.success) {
                        if (resp.data && resp.data.token) {
                            setJWT(resp.data.token)
                        }
                        return resp
                    }
                    throw resp
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
    registerWithToken(data) {
        try {
            const thiz = this
            return this.postWithJWT('registerWithToken', data)
                .then(resp => {
                    if (resp.success) {
                        if (resp.data && resp.data.token) {
                            setJWT(resp.data.token)
                        }
                        return resp
                    }
                    throw resp
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
    getUser(jwt) {
        try {
            return this.post('user', {token: jwt})
                .then(resp => resp.data)
        } catch (e) {
            delJWT()
            throw e
        }
    }

    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    logout() {
        try {
            return this.postWithJWT('logout')
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    getAddresses() {
        try {
            return this.getWithJWT('address', {orderBy: 'created_at', sortedBy: 'desc'})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    update(data) {
        try {
            return this.postWithJWT('update', data)
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    saveAddress(data) {
        try {
            return this.postWithJWT('address', data)
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    updateAddress(data) {
        try {
            return this.postWithJWT(`addressUpdate/${data.id}`, data)
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    deleteAddress(id) {
        try {
            return this.postWithJWT(`addressDelete/${id}`)
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    facebook(data) {
        try {
            const thiz = this
            return this.post('facebook', data)
                .then(resp => {
                    setJWT(resp.data.token)
                    return resp
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    getOrders() {
        try {
            return this.getWithJWT('order', {orderBy: 'created_at', sortedBy: 'desc'})
                .then(resp => resp.data)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @returns {PromiseLike<T> | Promise<T>}
     */
    removeFromFavourite(id, params = {}) {
        try {
            return this.postWithJWT(`removeFromFavourite/${id}`, params)
                .then(resp => {
                    return resp.data
                })
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    sendResetOrActivation(value) {
        try {
            return this.post('recover', {value})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    reSendSMSActivation(value) {
        try {
            return this.post('sms/verify', {value})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    sendCodeSMS(verification_code, phone_code, phone_number) {
        try {
            return this.post('sms/verify', {verification_code, phone_code, phone_number})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    changePassword({email, password, password_confirmation, token}) {
        try {
            return this.post('reset', {email, password, password_confirmation, token})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }


    /**
     *
     * @param phone_code
     * @param phone_number
     * @returns {PromiseLike<T> | Promise<T>}
     */
    sendContactCountry(data) {
        try {
            return this.post('contact/country', {data})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new UserService()