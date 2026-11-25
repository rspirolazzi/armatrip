import {createDuck} from 'redux-duck';
import _ from 'lodash'

const packageDuck = createDuck('package', 'armatrip');

/* Action types */
export const PACKAGES_GET = packageDuck.defineType('PACKAGES_GET')
export const PACKAGES_GET_PENDING = packageDuck.defineType('PACKAGES_GET_PENDING')
export const PACKAGES_GET_REJECTED = packageDuck.defineType('PACKAGES_GET_REJECTED')
export const PACKAGES_GET_FULFILLED = packageDuck.defineType('PACKAGES_GET_FULFILLED')
export const PACKAGE_GET = packageDuck.defineType('PACKAGE_GET')
export const PACKAGE_GET_REJECTED = packageDuck.defineType('PACKAGE_GET_REJECTED')
export const PACKAGE_GET_FULFILLED = packageDuck.defineType('PACKAGE_GET_FULFILLED')
export const PACKAGE_SET_CODE = packageDuck.defineType('PACKAGE_SET_CODE')
export const PACKAGE_SET = packageDuck.defineType('PACKAGE_SET')
export const PACKAGE_GROUP_SET = packageDuck.defineType('PACKAGE_GROUP_SET')

export const PACKAGES_SELECT_PRODUCT = packageDuck.defineType('PACKAGES_SELECT_PRODUCT')

export const PACKAGE_CLEAR = packageDuck.defineType('PACKAGE_CLEAR')

/* Action creators */
export const _getPackages = packageDuck.createAction(PACKAGES_GET);
export const _getPackage = packageDuck.createAction(PACKAGE_GET);
export const _setSelectedProduct = packageDuck.createAction(PACKAGES_SELECT_PRODUCT);
export const _setPackageCode = packageDuck.createAction(PACKAGE_SET_CODE);
export const _setPackage = packageDuck.createAction(PACKAGE_SET);
export const _setPackageGroup = packageDuck.createAction(PACKAGE_GROUP_SET);
export const _clearPackage = packageDuck.createAction(PACKAGE_CLEAR);


export const initialState = {
    all: [],
    updatedAt: null,
    isPending: false,
    isFulfilled: false,
    isRejected: false,
    lastError: false,
    detail: {},
    group: null,
}

/* Reducer */
const reducer = packageDuck.createReducer({
    [PACKAGE_SET_CODE]: (state, {payload}) => ({
        ...state,
        detail: {code: payload},
    }),
    [PACKAGE_SET]: (state, {payload}) => ({
        ...state,
        detail: {...payload},
    }),
    [PACKAGES_GET_PENDING]: (state, {payload}) => ({
        ...state,
        all: [], isPending: true, isFulfilled: false, isRejected: false, lastError: false,
    }),
    [PACKAGE_GET]: (state, {payload}) => ({
        ...state,
        isPending: true, lastError: false,
    }),
    [PACKAGES_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        /*all: [],*/
        updatedAt: new Date(),
        isPending: false,
        isFulfilled: false,
        isRejected: true,
        lastError: payload.error,
    }),
    [PACKAGE_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        /*all: [],*/
        updatedAt: new Date(), isPending: false, lastError: true,
    }),
    [PACKAGES_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        all: payload.data, updatedAt: new Date(), isPending: false, isFulfilled: true, isRejected: false
    }),
    [PACKAGE_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        detail: payload.data, updatedAt: new Date(), isPending: false
    }),
    [PACKAGES_SELECT_PRODUCT]: (state, {payload}) => {
        let detail = {...state.detail}
        if (payload.category_multiple) {
            detail.products = detail.products.map(product => {
                if (payload.selected === product.id) {
                    product.default = payload.check
                }
                return product
            })
        } else {
            detail.products = detail.products.map(product => {
                if (payload.selected === product.id) {
                    product.default = true
                } else {
                    let product_find = _.find(payload.productsFromCategory, {id: product.id})
                    if (product_find) {
                        product.default = false
                    }
                }
                return product
            })
        }
        return {
            ...state,
            detail: detail, updatedAt: new Date(), isPending: false
        }
    },
    [PACKAGE_GROUP_SET]: (state, {payload}) => ({
        ...state,
        group:payload,
    }),
    [PACKAGE_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer