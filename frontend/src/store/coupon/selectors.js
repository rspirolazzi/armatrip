import {createSelector} from 'reselect';
import {configurationStateSelector} from '../configuration/selectors'
import {packageDetailStateSelector} from '../package/selectors'

//export const couponStateSelector = state => state.coupon || {}


export const maxLengthCouponSelector = createSelector(
    [configurationStateSelector],
    (configuration) => {
        try {
            return configuration.coupon.maxLength
        } catch (error) {
            return 0
        }
    }
)
export const isVisibleCouponSelector = createSelector(
    [configurationStateSelector],
    (configuration) => {
        try {
            return configuration.coupon.isVisible === true
        } catch (error) {
            return false
        }
    }
)
export const addedCouponSelector = createSelector(
    [packageDetailStateSelector],
    (detail) => {
        try {
            return (detail !== undefined && detail.coupon !== undefined && detail.coupon.code !== undefined)
        } catch (error) {
            return false
        }
    }
)
export const couponStateSelector = createSelector(
    [packageDetailStateSelector],
    (detail) => {
        try {
            return detail.coupon
        } catch (error) {
            return {}
        }
    }
)
