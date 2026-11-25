import Base from './Base'

class NotificationService extends Base {

    getNotifications() {
        try {
            return this.get(`notifications`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }

    getNotificationByUri(uri) {
        try {
            return this.post(`notifications/uri`, {uri})
                .then(resp => resp)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new NotificationService()