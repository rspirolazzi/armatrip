import {applyMiddleware, createStore} from 'redux'
import {logger} from 'redux-logger'
import {persistReducer, persistStore} from 'redux-persist'
import rootReducers from './reducers'
import thunkMiddleware from 'redux-thunk'
import {createBlacklistFilter} from 'redux-persist-transform-filter'
import {
    jwtMiddleware,
    requestMiddlewareForAlert,
    requestMiddlewareForResponse,
    requestMiddlewareForToast
} from "./_middlewares"
import promiseMiddleware from 'redux-promise-middleware'
import storageSession from 'redux-persist/lib/storage/session';
import storage from 'redux-persist/lib/storage';
import order from "./order"
import product from "./product"
import store from "./store"
import province from "./province"
import brand from "./brand"
import notification from "./notification"
import core from "./core"
import {VERSION} from "../common/constants";

const ignoreCore = createBlacklistFilter(
    'core',
    ['message', 'title', 'isError', 'show', 'loading']
)
const ignoreProvinces = createBlacklistFilter(
    'province',
    ['all', 'province']
)
const ignoreBrands = createBlacklistFilter(
    'brand',
    ['all', 'brand']
)

const ignorePackages = createBlacklistFilter(
    'package',
    ['all', 'package']
)
const ignoreNotifications = createBlacklistFilter(
    'notification',
    ['all']
)

// Middlewares
const middlewares = [
    promiseMiddleware,
    thunkMiddleware,
    requestMiddlewareForAlert,
    requestMiddlewareForResponse,
    requestMiddlewareForToast,
    //handleOnPageMiddleware,
    jwtMiddleware,
]

if (process.env.NODE_ENV === `development`) {
    middlewares.push(logger)
}
const configuration = (history) => {
    const version = VERSION;

    // Persist
    const rootConfig = {
        key: version,
        storage: storage,
        blacklist: ['order', 'product', 'package', 'store', 'province', 'brand', 'notification', 'core', 'school',],
    };
    // Persist
    const sessionConfig = {
        key: version,
        transforms: [
            ignoreCore,
            ignoreProvinces,
            ignoreBrands,
            ignorePackages,
            ignoreNotifications,
        ],
        storage: storageSession,
        blacklist: ['user']
    };

    const persistedReducer = persistReducer(sessionConfig, rootReducers(history, rootConfig));

    let store = createStore(persistedReducer, applyMiddleware(...middlewares))
    let persistor = persistStore(store)
    return {store, persistor}
}
export default configuration