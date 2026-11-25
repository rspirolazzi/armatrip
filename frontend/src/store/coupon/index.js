import {createDuck} from 'redux-duck';

const couponDuck = createDuck('coupon', 'armatrip');

/* Action types */

export const REMOVE = couponDuck.defineType('REMOVE');
export const REMOVE_PENDING = couponDuck.defineType('REMOVE_PENDING');
export const REMOVE_REJECTED = couponDuck.defineType('REMOVE_REJECTED');
export const REMOVE_FULFILLED = couponDuck.defineType('REMOVE_FULFILLED');

export const ADD = couponDuck.defineType('ADD');
export const ADD_PENDING = couponDuck.defineType('ADD_PENDING');
export const ADD_REJECTED = couponDuck.defineType('ADD_REJECTED');
export const ADD_FULFILLED = couponDuck.defineType('ADD_FULFILLED');

/* Action creators */
export const _remove = couponDuck.createAction(REMOVE);
export const _apply = couponDuck.createAction(ADD);

export const initialState = {
    added: false,
    code: null,
    error:null,
    last_updated:null,
}

/* Reducer */
const reducer = couponDuck.createReducer({
    [ADD_PENDING]: (state, {payload}) => ({
        ...state,
        added: false, coupon: null, error:null, last_updated:new Date(),
    }),
    [ADD_REJECTED]: (state, {payload}) => ({
        ...state,
        added: false, coupon: null, error:payload, last_updated:new Date(),
    }),
    [ADD_FULFILLED]: (state, {payload}) => ({
        ...state,
        added: true, code: payload.code, error:null, last_updated:new Date(),
    }),
    [REMOVE_REJECTED]: (state, {payload}) => ({
        ...state,
        error:payload, last_updated:new Date(),
    }),
    [REMOVE_FULFILLED]: (state, {payload}) => ({
        ...state,
        added: false, code: null, error:null, last_updated:new Date(),
    }),
}, initialState)

export default reducer