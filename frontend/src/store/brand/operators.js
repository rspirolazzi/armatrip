import brandService from '../../services/Brand'

import {_getBrands,} from './index'

export const getBrands = () => _getBrands(brandService.getBrands())
