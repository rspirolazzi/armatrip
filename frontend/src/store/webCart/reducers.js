import * as types from './types'
import {addItemToWebCart, calculateWebCart, removeItemFromWebCart, updateItemToWebCart, WebCart} from "./helper";
import _ from 'lodash'


export default function reducer(state = {...WebCart}, {type, payload}) {
    switch (type) {

        case types.FETCHING_WEB_CART:
            return _.assign({}, state, {isPending: true})
        case types.FETCHED_WEB_CART:
            return _.assign({}, state, {isPending: false})
        case types.ADD_ITEM_WEB_CART:
            var {item, qty} = payload
            return addItemToWebCart(state, item, qty)
        case types.UPDATE_ITEM_WEB_CART:
            // eslint-disable-next-line
            var {index, item, qty} = payload
            return updateItemToWebCart(state, index, item, qty)
        case types.REMOVE_ITEM_WEB_CART:
            // eslint-disable-next-line
            var {item} = payload
            return removeItemFromWebCart(state, item)
        case types.SET_COMMENTS_WEB_CART:
            return _.assign({}, state, {comments: payload.comments})
        case types.REPEAT_ORDER:
            let newState = _.assign({}, state)
            const {all} = payload
            payload.orderItems.forEach(item => {
                var itemFind = _.assign({}, _.find(all, {name: item.name}))
                if (itemFind) {
                    var children = item.children.split(',')
                    itemFind.children = itemFind.children.filter(f => {
                        // eslint-disable-next-line
                        return _.find(children, (e) => e == f.name)
                    })
                    newState = addItemToWebCart(newState, itemFind, item.qty)
                }
            })
            return _.assign({}, newState, {lastModified: new Date()})
        case types.SET_FAVOURITE_WEB_CART:
            return _.assign({}, state, {favourite: payload.favourite})
        case types.CHECKOUT_WEB_CART:
            let last_order = _.assign({}, state, {
                finished: payload.finished,
                user: payload.user,
            }, payload.order)
            return _.assign({}, state, {finished: payload.finished, last_order})
        case types.REMOVE_ALL_ITEM_WEB_CART:
            return _.assign({}, WebCart, {last_order: state.last_order})
        case types.ADD_COUPON:
            return calculateWebCart(_.assign({}, state, {coupon:{code: payload.code, reward:payload.reward}}))
        case types.REMOVE_COUPON:
        case types.CLEAN_COUPON:
            return calculateWebCart(_.assign({}, state, {coupon:{}}))
        default:
            return state
    }
}