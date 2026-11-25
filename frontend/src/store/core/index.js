import {createDuck} from 'redux-duck';

const coreDuck = createDuck('core', 'armatrip');

/* Action types */
export const LOADING_SHOW = coreDuck.defineType('LOADING_SHOW')
export const LOADING_HIDE = coreDuck.defineType('LOADING_HIDE')
export const ALERT_SHOW = coreDuck.defineType('ALERT_SHOW')
export const ALERT_HIDE = coreDuck.defineType('ALERT_HIDE')

export const LOG_TO_SERVER_SET = coreDuck.defineType('LOG_TO_SERVER_SET')
export const LOG_TO_SERVER_SET_PENDING = coreDuck.defineType('LOG_TO_SERVER_SET_PENDING')
export const LOG_TO_SERVER_SET_REJECTED = coreDuck.defineType('LOG_TO_SERVER_SET_REJECTED')
export const LOG_TO_SERVER_SET_FULFILLED = coreDuck.defineType('LOG_TO_SERVER_SET_FULFILLED')

export const PARAMS_HEADER_SET = coreDuck.defineType('PARAMS_HEADER_SET')
export const TITLE_HEADER_SET = coreDuck.defineType('TITLE_HEADER_SET')


/* Action creators */
export const _showLoading = coreDuck.createAction(LOADING_SHOW);
export const _hideLoading = coreDuck.createAction(LOADING_HIDE);
export const _showAlert = coreDuck.createAction(ALERT_SHOW);
export const _hideAlert = coreDuck.createAction(ALERT_HIDE);
export const _setLogToServer = coreDuck.createAction(LOG_TO_SERVER_SET);
export const _setParamsHeader = coreDuck.createAction(PARAMS_HEADER_SET);


export const initialState = {
    loading: false,
    header: {
        images_: [],
        title: '',
        icon: '',
    },
}

/* Reducer */
const reducer = coreDuck.createReducer({
    [LOADING_SHOW]: (state, {payload}) => ({
        ...state,
        loading: true,
    }),
    [LOADING_HIDE]: (state, {payload}) => ({
        ...state,
        loading: false,
    }),
    [ALERT_SHOW]: (state, {payload}) => ({
        ...state,
        show: true,
        message: payload.message,
    }),
    [ALERT_HIDE]: (state, {payload}) => ({
        ...state,
        show: false,
        message: '',
    }),
    [PARAMS_HEADER_SET]: (state, {payload}) => ({
        ...state,
        show: false,
        header: {...payload},
    }),
}, initialState)

export default reducer