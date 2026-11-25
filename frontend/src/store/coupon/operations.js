import couponService from '../../services/Coupon'

import {_apply, _remove} from './index'

export const apply = code => _apply(couponService.apply({code}))
export const remove = code => _remove(couponService.remove({code}))