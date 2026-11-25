import {combineReducers} from 'redux';
import {persistReducer} from 'redux-persist';

import core from './core'
import product from './product'
import brand from './brand'
import order from './order'
import package_reducer from './package'
import store from './store'
import user from './user'
import province from './province'
import school from './school'
import notification from './notification'
import configuration from './configuration'
import coupon from './coupon'

export default (history, storagePersistConfig) => combineReducers({
    order,
    coupon,
    configuration,
    product,
    package: package_reducer,
    store,
    user: persistReducer(storagePersistConfig, user),
    province,
    brand,
    notification,
    core,
    school,
})
