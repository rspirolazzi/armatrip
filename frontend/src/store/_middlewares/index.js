import {toast} from "react-toastify"
import {_hideLoading, _showLoading} from "../core"
import {STORES_GET_REJECTED} from "../store"
import {NOTIFICATIONS_GET_REJECTED} from "../notification"
import {PACKAGE_GET_REJECTED} from "../package"
import {LOG_IN_REJECTED, LOGOUT_REJECTED, SET_JWT, SIGN_IN_REJECTED, VERIFICATION_SMS_REJECTED} from "../user"
import {PROVINCES_GET_REJECTED} from "../province"
import {setLogToServer} from "../core/operations"
import {SCHOOLS_GET_FULFILLED, SCHOOLS_GET_PENDING, SCHOOLS_GET_REJECTED} from "../school";
import {setJWT} from "../../services/Base";

const ignore_loading = [
    SCHOOLS_GET_PENDING,
    SCHOOLS_GET_REJECTED,
    SCHOOLS_GET_FULFILLED,
]
export const handleOnPageMiddleware = store => next => action => {
    /*switch (window.location.pathname) {
        case '/':
            break
        case '/stores':
            break
        case '/select-packages':
            if(store.getState().store){
                
            }
            break
        case '/register':
            if(store.getState().store){
                
            }
            break
        case '/mobile':
            break
        case '/select-date':
            break
        case '/activation':
            break
        case '/packages/:store_code':
            break
        case '/package/:code':
            break
        case '/share/:code':
            break
        case '/package/:code/services':
            break
        case '/package/:code/confirm':
            break
        case '/payment':
            break
        case '/finish':
            break
        case '/contact':
            break
        case '/register/contact':
            break
        case '/thanks':
            break
    }
    if (action.type.includes('_PENDING')) {
        store.dispatch(_showLoading())
    } else if (action.type.includes('_FULFILLED')) {
        store.dispatch(_hideLoading())
    } else if (action.type.includes('_REJECTED')) {
        store.dispatch(_hideLoading())
    }*/
    return next(action)
}
export const requestMiddlewareForResponse = store => next => action => {
    if (!ignore_loading.find(v => v === action.type)) {
        if (action.type.includes('_PENDING')) {
            store.dispatch(_showLoading())
        } else if (action.type.includes('_FULFILLED')) {
            store.dispatch(_hideLoading())
        } else if (action.type.includes('_REJECTED')) {
            store.dispatch(_hideLoading())
        }
    }
    return next(action)
}
export const requestMiddlewareForAlert = store => next => action => {
    if (action.type.includes('armatrip') && !action.type.includes('ALERT_') && action.payload !== undefined) {
        if (action.payload && action.payload.success === false) {
            //store.dispatch(_showAlert(action.payload))
            toast.error(action.payload.message, {
                toastId: action.type,
                position: toast.POSITION.BOTTOM_CENTER,
            })
            store.dispatch(setLogToServer(action.payload.message, action.payload))
        }
    }
    return next(action)
}
export const jwtMiddleware = store => next => action => {
    if (action.type.includes('armatrip') && action.type.includes('JWT') && action.payload !== undefined) {
        if (action.type === SET_JWT) {
            setJWT(action.payload)
        }
    }
    return next(action)
}
export const requestMiddlewareForToast = store => next => action => {
    if (action.type.includes('armatrip')
        && !action.type.includes('GET_USER_REJECTED')
        && !action.type.includes('ALERT_')
        && action.type.includes('_FULFILLED')
        && action.payload !== undefined) {
        if (action.payload && action.payload.success === true && action.payload.message) {
            toast.info(action.payload.message, {
                position: toast.POSITION.BOTTOM_CENTER,
            })
        }
    } else if (
        action.type.includes('armatrip') &&
        !action.type.includes('LOG_TO_SERVER_SET') &&
        !action.type.includes('ALERT_') &&
        !action.type.includes('GET_USER_REJECTED') &&
        action.type.includes('_REJECTED') &&
        action.payload !== undefined
    ) {
        store.dispatch(setLogToServer(action.payload.message, action.payload))
        let message = null
        if (action.payload && action.payload.success === false && action.payload.message) {
            message = action.payload.message
        } else if (action.payload && action.payload.message) {
            message = action.payload.message + ` (${action.type})`
            switch (action.type) {
                case STORES_GET_REJECTED:
                case NOTIFICATIONS_GET_REJECTED:
                case PACKAGE_GET_REJECTED:
                case LOGOUT_REJECTED:
                case LOG_IN_REJECTED:
                case VERIFICATION_SMS_REJECTED:
                case SIGN_IN_REJECTED:
                case PROVINCES_GET_REJECTED:
                    message = `Error al conectarse con el servidor (${action.type})`
                    break;
            }
        }
        if (message) {
            toast.error(message, {
                toastId: action.type,
                position: toast.POSITION.BOTTOM_CENTER,
            })
        }
    }
    return next(action)
}