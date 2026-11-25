import {createSelector} from 'reselect';
import _ from 'lodash'

const notificationsByUriStateSelector = (state, props) => {
    try {
        const filter = _.filter(state.notification.all, (notification) => {
            const uri = props.history.location.pathname
            const bool = (notification.uri === '' ||
                notification.uri === undefined ||
                notification.uri === null ||
                notification.uri === '*' ||
                uri.toString().startsWith(notification.uri.toString().replace(/\*/g, ''))
            )
            return bool
        });
        return _.sortBy(filter, 'order')
    } catch (e) {
        return []
    }
}


export const getNotificationsSelector = createSelector(
    [notificationsByUriStateSelector],
    (notifications) => {
        try {
            return _.filter(notifications, (o)=>o.is_actions != true)
        } catch (error) {
            return []
        }
    }
)

export const getActionNotificationsSelector = createSelector(
    [notificationsByUriStateSelector],
    (notifications) => {
        try {
            return _.filter(notifications, (e) => (e.is_actions === 1 || e.is_actions === true))
        } catch (error) {
            return []
        }
    }
)

export const getOneNotificationSelector = createSelector(
    [getNotificationsSelector],
    (notifications) => {
        try {
            return notifications.length > 0 ? notifications[0] : null
        } catch (error) {
            return []
        }
    }
)