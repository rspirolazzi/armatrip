import {createDuck} from 'redux-duck'

const user = createDuck('province', 'armatrip')

/* Action types */
export const PROVINCES_GET = user.defineType('PROVINCES_GET')
export const PROVINCES_GET_FULFILLED = user.defineType('PROVINCES_GET_FULFILLED')
export const PROVINCES_GET_PENDING = user.defineType('PROVINCES_GET_PENDING')
export const PROVINCES_GET_REJECTED = user.defineType('PROVINCES_GET_REJECTED')

export const PROVINCE_SET = user.defineType('PROVINCE_SET')

export const PROVINCE_CLEAR = user.defineType('PROVINCE_CLEAR')

/*Action Creators*/
export const _getProvinces = user.createAction(PROVINCES_GET)
export const _setProvince = user.createAction(PROVINCE_SET)
export const _clearProvince = user.createAction(PROVINCE_CLEAR)


/* Reducer */
const initialState = {
    provinces: [],
    province: null,
    isPending: false,
    isRejected: false,
    isFulfilled: false,
    lastError: null,
}
/* Reducer */
const reducer = user.createReducer({
    [PROVINCE_SET]: (state, {payload}) => ({
        ...state,
        province: payload,
    }),
    [PROVINCES_GET]: (state, {payload}) => ({
        ...state,
        provinces: [],
        isPending: false,
        isFulfilled: false,
        isRejected: false,
        lastError: null,
    }),
    [PROVINCES_GET_PENDING]: (state, {payload}) => ({
        ...state,
        provinces: [],
        isPending: true,
        isFulfilled: false,
        isRejected: false,
        lastError: null,
    }),
    [PROVINCES_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        provinces: payload.data,
        isPending: false,
        isFulfilled: true,
        isRejected: false,
        lastError: null,
    }),
    [PROVINCES_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        provinces: [],
        isPending: false,
        isFulfilled: false,
        isRejected: true,
        lastError: payload.error,
    }),
    [PROVINCE_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer