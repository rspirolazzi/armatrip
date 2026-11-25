import {createDuck} from 'redux-duck';
import _ from 'lodash'

const notificationDuck = createDuck('notification', 'armatrip');

/* Action types */
export const NOTIFICATIONS_GET = notificationDuck.defineType('NOTIFICATIONS_GET')
export const NOTIFICATIONS_GET_PENDING = notificationDuck.defineType('NOTIFICATIONS_GET_PENDING')
export const NOTIFICATIONS_GET_REJECTED = notificationDuck.defineType('NOTIFICATIONS_GET_REJECTED')
export const NOTIFICATIONS_GET_FULFILLED = notificationDuck.defineType('NOTIFICATIONS_GET_FULFILLED')

export const NOTIFICATION_REMOVE = notificationDuck.defineType('NOTIFICATION_REMOVE')
export const NOTIFICATION_CLEAR = notificationDuck.defineType('NOTIFICATION_CLEAR')

/* Action creators */
export const _getNotifications = notificationDuck.createAction(NOTIFICATIONS_GET);
export const _clearNotification = notificationDuck.createAction(NOTIFICATION_CLEAR);
export const _notificationRemove = notificationDuck.createAction(NOTIFICATION_REMOVE);


export const initialState = {
    all: [],
    updatedAt: null,
    isPending: false,
    isFulfilled: false,
    isRejected: false,
    lastError: false,
}

/* Reducer */
const reducer = notificationDuck.createReducer({
    [NOTIFICATIONS_GET_PENDING]: (state, {payload}) => ({
        ...state,
        all: [], isPending: true, isFulfilled: false, isRejected: false, lastError: false,
    }),
    [NOTIFICATIONS_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        all: [],
        updatedAt: new Date(),
        isPending: false,
        isFulfilled: false,
        isRejected: true,
        lastError: payload.error,
    }),
    [NOTIFICATIONS_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        all: payload.data, updatedAt: new Date(), isPending: false, isFulfilled: true, isRejected: false
    }),
    [NOTIFICATION_REMOVE]: (state, {payload}) => {
        const all = _.filter(state.all, (e) => e.id !== payload)
        return {
            ...state,
            all: all, updatedAt: new Date(), isPending: false, lastError: true,
        }
    },
    [NOTIFICATION_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer