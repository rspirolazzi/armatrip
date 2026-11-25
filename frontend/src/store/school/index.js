import {createDuck} from 'redux-duck';

const schoolDuck = createDuck('school', 'armatrip');

/* Action types */
export const SCHOOLS_GET = schoolDuck.defineType('SCHOOLS_GET')
export const SCHOOLS_GET_PENDING = schoolDuck.defineType('SCHOOLS_GET_PENDING')
export const SCHOOLS_GET_REJECTED = schoolDuck.defineType('SCHOOLS_GET_REJECTED')
export const SCHOOLS_GET_FULFILLED = schoolDuck.defineType('SCHOOLS_GET_FULFILLED')

/* Action creators */
export const _getSchools = schoolDuck.createAction(SCHOOLS_GET);

export const initialState = {
    all: [],
    updatedAt: null,
    isPending: false,
    isRejected: false,
    isFulfilled: false,
    lastError: false,
}

/* Reducer */
const reducer = schoolDuck.createReducer({
    [SCHOOLS_GET]: (state, {payload}) => ({
        ...state,
        all: [],
        updatedAt: null,
        isPending: false,
        isRejected: false,
        isFulfilled: false,
        lastError: false,
    }),
    [SCHOOLS_GET_PENDING]: (state, {payload}) => ({
        ...state,
        isPending: true,
    }),
    [SCHOOLS_GET_REJECTED]: (state, {payload}) => ({
        ...state,
        isPending: false,
        lastError: payload.error,
        isRejected: true,
    }),
    [SCHOOLS_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        all: payload.data,
        updatedAt: new Date(),
        isPending: false,
        isFulfilled: true
    }),
}, initialState)

export default reducer