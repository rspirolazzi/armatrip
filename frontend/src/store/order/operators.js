import orderService from '../../services/Order'

import {_getOrderShare, _paymentOrder, _saveOrder} from './index'

export const postOrder = (data) => _saveOrder(orderService.checkout(data))
export const payment = (data) => _paymentOrder(orderService.payment(data))
export const getOrderShare = (code) => _getOrderShare(orderService.getOrderShow(code))
