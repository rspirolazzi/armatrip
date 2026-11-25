import * as types from './types'
import webCartService from '../../services/Order'

const defaultOnError = (data) => {
    //console.error('defaultOnError', data)
}
const defaultOnSuccess = (data) => {
    //console.log('defaultOnSuccess', data)
}
export const addItemToWebCart = (item, qty = 1) => (dispatch, getState) => {
    dispatch({type: types.ADD_ITEM_WEB_CART, payload: {item: (item), qty}})
}
export const updateItemToWebCart = (index, item, qty = 1) => (dispatch, getState) => {
    dispatch({type: types.UPDATE_ITEM_WEB_CART, payload: {index, item, qty}})
}
export const removeItemToWebCart = (item) => (dispatch, getState) => {
    dispatch({type: types.REMOVE_ITEM_WEB_CART, payload: {item}})
}
export const removeAllItemsToWebCart = () => (dispatch, getState) => cleanWebCart()

export const cleanWebCart = ({type: types.REMOVE_ALL_ITEM_WEB_CART})

export const checkout = (data, onSuccess = defaultOnSuccess, onError = defaultOnError) => (dispatch, getState) => {

    dispatch({type: types.FETCHING_WEB_CART})
    let params = data
    params.orderItems = getState().webCart.items
    params.subTotal = getState().webCart.subTotal
    params.delivery = getState().webCart.delivery
    params.total = getState().webCart.total
    params.comments = getState().webCart.comments
    params.favourite = getState().webCart.favourite
    params.address_id = getState().user.selectedAddress.id
    if(getState().webCart.coupon){
        params.coupon = getState().webCart.coupon
    }
    webCartService.checkout({...params, store_id: getState().store.selected.id})
        .then(resp => {

            dispatch({type: types.FETCHED_WEB_CART})
            dispatch({type: types.CHECKOUT_WEB_CART, payload: {finished: true, order: resp, user: getState().user}})
            dispatch({type: types.REMOVE_ALL_ITEM_WEB_CART})
            onSuccess(resp)
        })
        .catch(resp => {
            onError(resp)
            dispatch({type: types.FETCHED_WEB_CART})
        })
}
export const setComments = (comments) => (dispatch, getState) => {
    dispatch({type: types.SET_COMMENTS_WEB_CART, payload: {comments}})
}

export const setFavourite = (favourite) => (dispatch, getState) => {
    dispatch({type: types.SET_FAVOURITE_WEB_CART, payload: {favourite}})
}

export const orderFromFavourite = (orderItems, ...rest) => (dispatch, getState) => {
    dispatch({type: types.REPEAT_ORDER, payload: {orderItems, all: getState().product.all}})
}


export const addCoupon = (code, reward) => ({type: types.ADD_COUPON, payload: {code, reward}})
export const removeCoupon = (code) => ({type: types.REMOVE_COUPON, payload: {code}})
export const cleanCoupon = () => ({type: types.CLEAN_COUPON, payload: {}})
