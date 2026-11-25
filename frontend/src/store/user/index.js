import {createDuck} from 'redux-duck'

const user = createDuck('user', 'armatrip')

/* Action types */
export const PRE_REGISTER = user.defineType('PRE_REGISTER')
export const SEND_PHONE = user.defineType('SEND_PHONE')
export const SIGN_IN = user.defineType('SIGN_IN')
export const SIGN_IN_FULFILLED = user.defineType('SIGN_IN_FULFILLED')
export const SIGN_IN_PENDING = user.defineType('SIGN_IN_PENDING')
export const SIGN_IN_REJECTED = user.defineType('SIGN_IN_REJECTED')

export const VERIFICATION_SMS = user.defineType('VERIFICATION_SMS')
export const VERIFICATION_SMS_FULFILLED = user.defineType('VERIFICATION_SMS_FULFILLED')
export const VERIFICATION_SMS_PENDING = user.defineType('VERIFICATION_SMS_PENDING')
export const VERIFICATION_SMS_REJECTED = user.defineType('VERIFICATION_SMS_REJECTED')

export const SEND_CONTACT_COUNTRY = user.defineType('SEND_CONTACT_COUNTRY')

export const LOG_IN = user.defineType('LOG_IN')
export const LOG_IN_FULFILLED = user.defineType('LOG_IN_FULFILLED')
export const LOG_IN_PENDING = user.defineType('LOG_IN_PENDING')
export const LOG_IN_REJECTED = user.defineType('LOG_IN_REJECTED')
export const GET_USER = user.defineType('GET_USER')
export const GET_USER_FULFILLED = user.defineType('GET_USER_FULFILLED')
export const GET_USER_PENDING = user.defineType('GET_USER_PENDING')
export const GET_USER_REJECTED = user.defineType('GET_USER_REJECTED')
export const LOGOUT = user.defineType('LOGOUT')
export const LOGOUT_FULFILLED = user.defineType('LOGOUT_FULFILLED')
export const LOGOUT_PENDING = user.defineType('LOGOUT_PENDING')
export const LOGOUT_REJECTED = user.defineType('LOGOUT_REJECTED')
export const PARTIAL_LOGOUT = user.defineType('PARTIAL_LOGOUT')

export const SET_USER = user.defineType('SET_USER')
export const SET_JWT = user.defineType('SET_JWT')
export const USER_CLEAR = user.defineType('USER_CLEAR')
export const USER_IS_VALID_CLEAR = user.defineType('USER_IS_VALID_CLEAR')

/*Action Creators*/
export const _preRegister = user.createAction(PRE_REGISTER)
export const _sendPhone = user.createAction(SEND_PHONE)
export const _verificationSMS = user.createAction(VERIFICATION_SMS)
export const _logIn = user.createAction(LOG_IN)
export const _setJWT = user.createAction(SET_JWT)
export const _setUser = user.createAction(SET_USER)
export const _getUser = user.createAction(GET_USER)
export const _signIn = user.createAction(SIGN_IN)
export const _logout = user.createAction(LOGOUT)
export const _clearUser = user.createAction(USER_CLEAR)
export const _sendContactCountry = user.createAction(SEND_CONTACT_COUNTRY)
export const _setUserIsValidClear = user.createAction(USER_IS_VALID_CLEAR)

/* Reducer */
const initialState = {
    user: null,
    jwt: null,
    isPending: false,
    isRejected: false,
    isFulfilled: false,
    lastError: null,
    isLoggedIn: false,
    isValid: false,
}
/* Reducer */
const reducer = user.createReducer({
    [USER_IS_VALID_CLEAR]: (state) => ({
        ...state,
        isValid: false,
        jwt:null,
    }),
    [GET_USER_PENDING]: (state, {payload}) => ({
        ...state,
        isLoggedIn: false,
        isPending: true,
    }),
    [GET_USER_FULFILLED]: (state, {payload}) => ({
        ...state,
        user: {...state.user, ...payload},
        isLoggedIn: true,
        updatedAt: new Date(),
        isPending: false,
        isRejected: false,
        isFulfilled: true,
        lastError: null,
        isValid: true,
    }),
    [GET_USER_REJECTED]: (state, {payload}) => ({
        ...state,
        isLoggedIn: false,
        updatedAt: new Date(),
        isPending: false,
        isRejected: true,
        isFulfilled: false,
        lastError: payload,
        isValid: false,
    }),
    [PRE_REGISTER]: (state, {payload}) => ({
        ...state,
        user: {...state.user, ...payload},
        isLoggedIn: false,
        updatedAt: new Date(),
        isPending: false,
        isRejected: false,
        isFulfilled: false,
        lastError: null,
    }),
    [SEND_PHONE]: (state, {payload}) => ({
        ...state,
        sendPhone: payload,
    }),
    [LOG_IN]: (state, {payload}) => ({
        ...state,
        user: null,
        isLoggedIn: false,
        updatedAt: null,
        isPending: false,
        isRejected: false,
        isFulfilled: false,
        lastError: null,
    }),
    [LOG_IN_PENDING]: (state, {payload}) => ({
        ...state,
        isPending: true,
    }),
    [LOG_IN_FULFILLED]: (state, {payload}) => ({
        ...state,
        user: payload.data,
        isLoggedIn: true,
        updatedAt: new Date(),
        isPending: false,
        isFulfilled: true,
    }),
    [LOG_IN_REJECTED]: (state, {payload}) => ({
        ...state,
        user: null,
        isLoggedIn: false,
        updatedAt: null,
        isPending: false,
        lastError: payload.error,
        isRejected: true,
    }),

    [SIGN_IN]: (state, {payload}) => ({
        ...state,
        isLoggedIn: false,
        updatedAt: null,
        isPending: false,
        lastError: null,
        isRejected: false,
    }),
    [SIGN_IN_PENDING]: (state, {payload}) => ({
        ...state,
        isLoggedIn: false,
        updatedAt: null,
        isPending: true,
        lastError: null,
        isRejected: false,
    }),
    [SIGN_IN_FULFILLED]: (state, {payload}) => ({
        ...state,
        isLoggedIn: true,
        updatedAt: new Date(),
        isPending: false
    }),
    [SIGN_IN_REJECTED]: (state, {payload}) => ({
        ...state,
        isLoggedIn: false,
        updatedAt: null,
        isPending: false,
        lastError: payload.error,
        isRejected: true,
    }),

    [VERIFICATION_SMS]: (state, {payload}) => ({
        ...state,
        isPending: false,
        lastError: null,
        isRejected: false,
        isValid: false,
    }),
    [VERIFICATION_SMS_PENDING]: (state, {payload}) => ({
        ...state,
        isPending: true,
        isFulfilled: false,
        lastError: null,
        isRejected: false,
        isValid: false,
    }),
    [VERIFICATION_SMS_FULFILLED]: (state, {payload}) => ({
        ...state,
        user: {...state.user, ...payload.data.user},
        jwt: payload.data.token,
        isLoggedIn: true,
        isPending: false,
        isFulfilled: true,
        lastError: null,
        isRejected: false,
        isValid: true,
    })
    ,
    [VERIFICATION_SMS_REJECTED]: (state, {payload}) => ({
        ...state,
        isPending: false,
        isFulfilled: false,
        lastError: payload.error,
        isRejected: true,
        isValid: false,
    }),

    [SEND_CONTACT_COUNTRY]: (state, {payload}) => ({
        ...state,
        isPending: false,
        lastError: null,
    }),
    [SET_USER]: (state, {payload}) => ({
        ...state,
        user: {...payload},
    }),
    [SET_JWT]: (state, {payload}) => ({
        ...state,
        jwt: payload,
    }),
    [LOGOUT]: (state, {payload}) => ({
        ...initialState,
    }),
    [LOGOUT_FULFILLED]: (state, {payload}) => ({
        ...initialState,
    }),
    [LOGOUT_REJECTED]: (state, {payload}) => ({
        ...initialState,
    }),
    [PARTIAL_LOGOUT]: (state, {payload}) => ({
        ...initialState
    }),
    [USER_CLEAR]: (state, {payload}) => ({
        ...initialState
    }),
}, initialState)

export default reducer