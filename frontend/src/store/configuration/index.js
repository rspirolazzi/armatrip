import {createDuck} from 'redux-duck';

const configurationDuck = createDuck('coupon', 'armatrip');

/* Action types */
export const CONFIGURATION_GET = configurationDuck.defineType('CONFIGURATION_GET');
export const CONFIGURATION_GET_PENDING = configurationDuck.defineType('CONFIGURATION_GET_PENDING');
export const CONFIGURATION_GET_FULFILLED = configurationDuck.defineType('CONFIGURATION_GET_FULFILLED');
export const CONFIGURATION_GET_REJECTED = configurationDuck.defineType('CONFIGURATION_GET_REJECTED');
/* Action creators */
export const _getConfigurations = configurationDuck.createAction(CONFIGURATION_GET);

export const initialState = {
    individual_tourism: {
        code: 'turismo-individual',
        url: '/turismo-individual',
    },
}

/* Reducer */
const reducer = configurationDuck.createReducer({
    [CONFIGURATION_GET_PENDING]: (state, {payload}) => ({
        ...state,
    }),
    [CONFIGURATION_GET_REJECTED]: (state, {payload}) => ({
        ...state,
    }),
    [CONFIGURATION_GET_FULFILLED]: (state, {payload}) => ({
        ...state,
        ...payload
    }),
}, initialState)

export default reducer