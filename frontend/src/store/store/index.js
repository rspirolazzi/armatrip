import {createDuck} from 'redux-duck';

const storeDuck = createDuck('store', 'armatrip');

/* Action types */
export const STORES_GET = storeDuck.defineType('STORES_GET')
export const STORES_GET_PENDING = storeDuck.defineType('STORES_GET_PENDING')
export const STORES_GET_REJECTED = storeDuck.defineType('STORES_GET_REJECTED')
export const STORES_GET_FULFILLED = storeDuck.defineType('STORES_GET_FULFILLED')
export const STORES_SELECTED = storeDuck.defineType('STORES_SELECTED')

export const STORE_CLEAR = storeDuck.defineType('STORE_CLEAR')

/* Action creators */
export const _getStores = storeDuck.createAction(STORES_GET);
export const _selectedStore = storeDuck.createAction(STORES_SELECTED);
export const _clearStore = storeDuck.createAction(STORE_CLEAR);


export const initialState = {
    'all': [],
    'updatedAt': null,
    'isPending': false,
    'isRejected': false,
    'isFulfilled': false,
    'lastError': false,
    'selected': null,
}

/* Reducer */
const reducer = storeDuck.createReducer({
    [STORES_GET]: (state, {payload}) => ({
        ...state,
        all: [],
        updatedAt: null,
        isPending: false,
        isRejected: false,
        isFulfilled: false,
        lastError: false,
        selected: null,
    }),
    [STORES_GET_PENDING]: (state, {payload}) => ({
        ...state,
        isPending: true,
    }),
    [STORES_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        isPending: false, lastError: payload.error, isRejected: true,
    }),
    [STORES_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        all: payload.data, updatedAt: new Date(), isPending: false, isFulfilled: true
    }),
    [STORES_SELECTED]: (state, {payload}) => ({
        ...state,
        selected: payload, updatedAt: new Date(),
    }),
    [STORE_CLEAR]: () => ({
        ...initialState
    }),
}, initialState)

export default reducer