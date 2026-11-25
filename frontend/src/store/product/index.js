import {createDuck} from 'redux-duck';

const product = createDuck('product', 'armatrip');

/* Action types */
export const PRODUCTS_GET = product.defineType('PRODUCTS_GET')
export const PRODUCT_GET = product.defineType('PRODUCT_GET')
export const PRODUCTS_GET_REJECTED = product.defineType('PRODUCTS_GET_REJECTED')
export const PRODUCT_GET_REJECTED = product.defineType('PRODUCT_GET_REJECTED')
export const PRODUCT_GET_FULFILLED = product.defineType('PRODUCT_GET_FULFILLED')
export const PRODUCTS_GET_FULFILLED = product.defineType('PRODUCTS_GET_FULFILLED')

export const PRODUCT_CLEAR = product.defineType('PRODUCT_CLEAR')

/* Action creators */
export const _getProducts = product.createAction(PRODUCTS_GET);
export const _getProduct = product.createAction(PRODUCT_GET);
export const _clearProduct = product.createAction(PRODUCT_CLEAR);


export const initialState = {
    'all': [],
    'updatedAt': null,
    'isPending': false,
    'lastError': false,
    'detail': {},
}
export const Product = {
    "id": null,
    "name": null,
    "description": null,
    "image": null,
    "price": null,
    "order": null,
    "min": null,
    "max": null,
    "category": null,

}

/* Reducer */
const reducer = product.createReducer({
    [PRODUCTS_GET]: (state, {payload}) => ({
        ...state,
        all: [], isPending: true, lastError: false,
    }),
    [PRODUCT_GET]: (state, {payload}) => ({
        ...state,
        isPending: true, lastError: false,
    }),
    [PRODUCTS_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        all: [], updatedAt: new Date(), isPending: false, lastError: true,
    }),
    [PRODUCT_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        all: [], updatedAt: new Date(), isPending: false, lastError: true,
    }),
    [PRODUCTS_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        all: payload.data, updatedAt: new Date(), isPending: false
    }),
    [PRODUCT_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        detail: payload.data, updatedAt: new Date(), isPending: false
    }),
    [PRODUCT_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer