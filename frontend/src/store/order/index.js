import {createDuck} from 'redux-duck'

const user = createDuck('order', 'armatrip')

/* Action types */
export const ORDERS_SHARE_GET = user.defineType('ORDERS_SHARE_GET')
export const ORDERS_SHARE_GET_FULFILLED = user.defineType('ORDERS_SHARE_GET_FULFILLED')
export const ORDERS_SHARE_GET_PENDING = user.defineType('ORDERS_SHARE_GET_PENDING')
export const ORDERS_SHARE_GET_REJECTED = user.defineType('ORDERS_SHARE_GET_REJECTED')
export const ORDERS_GET = user.defineType('ORDERS_GET')
export const ORDERS_GET_FULFILLED = user.defineType('ORDERS_GET_FULFILLED')
export const ORDERS_GET_PENDING = user.defineType('ORDERS_GET_PENDING')
export const ORDERS_GET_REJECTED = user.defineType('ORDERS_GET_REJECTED')

export const ORDER_SET_DATE = user.defineType('ORDER_SET_DATE')
export const ORDER_PAYMENT = user.defineType('ORDER_PAYMENT')
export const ORDER_SAVE = user.defineType('ORDER_SAVE')
export const ORDER_SAVE_FULFILLED = user.defineType('ORDER_SAVE_FULFILLED')
export const ORDER_SAVE_PENDING = user.defineType('ORDER_SAVE_PENDING')
export const ORDER_SAVE_REJECTED = user.defineType('ORDER_SAVE_REJECTED')

export const ORDER_CLEAR = user.defineType('ORDER_CLEAR')
export const ORDER_HAS_BEEN_CHECKOUT = user.defineType('ORDER_HAS_BEEN_CHECKOUT')

/*Action Creators*/
export const _getOrders = user.createAction(ORDERS_GET)
export const _getOrderShare = user.createAction(ORDERS_SHARE_GET)
export const _setOrderDate = user.createAction(ORDER_SET_DATE)
export const _saveOrder = user.createAction(ORDER_SAVE)
export const _paymentOrder = user.createAction(ORDER_PAYMENT)
export const _clearOrder = user.createAction(ORDER_CLEAR)
export const _setOrderHasCheckout = user.createAction(ORDER_HAS_BEEN_CHECKOUT)

/* Reducer */
const initialState = {
    orders: [],
    date: null,
    isPending: false,
    isRejected: false,
    isFulfilled: false,
    lastError: null,
    isLoggedIn: false,
    checkout:false,
    order_share:null,
    order:null,
}
/* Reducer */
const reducer = user.createReducer({
    [ORDERS_SHARE_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        order_share: payload,
    }),
    [ORDERS_SHARE_GET_PENDING]: (state) => ({
        ...state,
        order_share: null,
    }),
    [ORDERS_SHARE_GET_REJECTED]: (state) => ({
        ...state,
        order_share: null,
    }),
    [ORDER_SET_DATE]: (state, {payload}) => ({
        ...state,
        date: payload,
    }),
    [ORDERS_GET]: (state, {payload}) => ({
        ...state,
        orders: [],
        isPending: true,
        lastError: null,
        isRejected: false
    }),
    [ORDERS_GET_PENDING]: (state, {payload}) => ({
        ...state,
        orders: [],
        isPending: true,
        lastError: null,
        isRejected: false
    }),
    [ORDERS_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        orders: payload.data,
        isPending: false,
        lastError: payload.error,
        isRejected: true
    }),
    [ORDERS_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        orders: [],
        isPending: false,
        lastError: payload.error,
        isRejected: true
    }),

    [ORDER_SAVE]: (state, {payload}) => ({
        ...state,
        isPending: false,
        lastError: null,
        isRejected: false,
        isFulfilled: false,
    }),
    [ORDER_SAVE_PENDING]: (state, {payload}) => ({
        ...state,
        isPending: true,
    }),
    [ORDER_SAVE_FULFILLED]: (state, {payload}) => ({
        ...state,
        isPending: false,
        isFulfilled: true,
        checkout:true,
        order:{...payload}
    }),
    [ORDER_SAVE_REJECTED]: (state, {payload}) => ({
        ...state,
        isPending: false,
        lastError: payload.error,
        isRejected: true
    }),
    [ORDER_HAS_BEEN_CHECKOUT]: (state, {payload}) => ({
        ...state,
        checkout: payload
    }),
    [ORDER_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer