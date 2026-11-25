import provinceService from '../../services/Province'

import {_getProvinces,} from './index'

export const getProvinces = (store) => _getProvinces(provinceService.getProvinces(store))
