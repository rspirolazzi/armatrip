import {createDuck} from 'redux-duck'

const user = createDuck('brand', 'armatrip')

/* Action types */
export const BRANDS_GET = user.defineType('BRANDS_GET')
export const BRANDS_GET_FULFILLED = user.defineType('BRANDS_GET_FULFILLED')
export const BRANDS_GET_PENDING = user.defineType('BRANDS_GET_PENDING')
export const BRANDS_GET_REJECTED = user.defineType('BRANDS_GET_REJECTED')

export const BRAND_SET = user.defineType('BRAND_SET')

export const BRAND_CLEAR = user.defineType('BRAND_CLEAR')

/*Action Creators*/
export const _getBrands = user.createAction(BRANDS_GET)
export const _setBrand = user.createAction(BRAND_SET)
export const _clearBrand = user.createAction(BRAND_CLEAR)


/* Reducer */
const initialState = {
    brands: [],
    brand: null,
    isPending: false,
    isRejected: false,
    isFulfilled: false,
    lastError: null,
}
/* Reducer */
const reducer = user.createReducer({
    [BRAND_SET]: (state, {payload}) => ({
        ...state,
        brand: payload,
    }),
    [BRANDS_GET]: (state, {payload}) => ({
        ...state,
        brands: [],
        isPending: false,
        isFulfilled: false,
        isRejected: false,
        lastError: null,
    }),
    [BRANDS_GET_PENDING]: (state, {payload}) => ({
        ...state,
        brands: [],
        isPending: true,
        isFulfilled: false,
        isRejected: false,
        lastError: null,
    }),
    [BRANDS_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        brands: payload.data,
        isPending: false,
        isFulfilled: true,
        isRejected: false,
        lastError: null,
    }),
    [BRANDS_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        brands: [],
        isPending: false,
        isFulfilled: false,
        isRejected: true,
        lastError: payload.error,
    }),
    [BRAND_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer