import storeService from '../../services/Store'
import {_getStores, _selectedStore} from './index'

export const getStores = () => _getStores(storeService.getStores())
export const selectStore = (store) => _selectedStore(store)