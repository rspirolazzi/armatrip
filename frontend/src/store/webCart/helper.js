//import {Record, List} from 'immutable'
import _ from 'lodash'
import moment from 'moment'
/*export const WebCart= Record({
    date:null,
    user:null,
    items:new List(),
})
export const WebCartItem= Record({
    item:null,
    qty:null,
})*/
export const WebCart = {
    date: null,
    user: null,
    items: [],
    subTotal: 0,
    total: 0,
    discount: 0,
    delivery: 0,
    delivery_time: '',
    comments: '',
    isPending: false,
    finished: false,
    last_order: {},
    favourite: false,
    coupon: {},
    lastModified: new Date(),
    persistExpiresAt: moment()
        .add(5, 'm')
        .format(),
}
export const WebCartItem = {
    item: null,
    qty: null,
    price: null,
    children: [],
}
export const calculateWebCart = (webCart) => {
    webCart.discount=0
    webCart.subTotal = parseFloat(webCart.items.reduce((a, b) => (a + (b.total)), 0))
    if(webCart.coupon !== undefined && webCart.coupon.code !==undefined && webCart.coupon.reward !==undefined){
        webCart.discount = (parseFloat(webCart.coupon.reward)/100) * webCart.subTotal
    }
    webCart.total = webCart.subTotal + parseFloat(webCart.delivery) - parseFloat(webCart.discount)
    return _.assign({}, webCart)
}
export const addItemToWebCart = (webCart, item, qty = 1) => {

    const items = webCart.items
    items.push({...item, qty: parseInt(qty, 0), total: parseFloat(item.price) * parseInt(qty, 0)})
    webCart.items = items
    webCart.finished = false
    webCart = calculateWebCart(webCart)
    return _.assign({}, webCart)
}
export const updateItemToWebCart = (webCart, index, item, qty = 1) => {
    if (webCart.items[index] !== undefined) {
        const items = webCart.items.map((_item, i) => {
            // eslint-disable-next-line
            if (i === index && _item.id == item.id) {
                _item.qty = parseInt(qty, 0)
                _item.total = parseFloat(_item.price) * parseInt(qty, 0)
                _item.children = item.children
            }
            return _item
        })
        webCart.items = items
        webCart.finished = false
        webCart = calculateWebCart(webCart)
    } else {
        //console.error('No se encontro el item con index ', index)
    }
    return _.assign({}, webCart)
}
export const removeItemFromWebCart = (webCart, item) => {
    var {items} = webCart
    var _index = items.indexOf(item);
    if (_index > -1) {
        items.splice(_index, 1);
    }
    webCart.finished = false
    webCart.items = items
    webCart = calculateWebCart(webCart)
    return _.assign({}, webCart)
}