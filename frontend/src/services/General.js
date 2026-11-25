import Base from './Base'

class ContactService extends Base {
    /**
     *
     * @param email
     * @param password
     * @returns {PromiseLike<T> | Promise<T>}
     */
    send(data) {
        try {
            return this.post('contact', data)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new ContactService()