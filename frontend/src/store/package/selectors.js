import {createSelector} from 'reselect';
import _ from 'lodash'
import moment from "moment";

const packagesStateSelector = state => state.package.all
const packageStateSelector = state => state.package
export const packageDetailStateSelector = state => state.package ? state.package.detail || {} : {}
const packagePriceBaseStateSelector = state => state.package.detail ? state.package.detail.price : 0
const packageProductsStateSelector = state => (state.package && state.package.detail) ? state.package.detail.products : []

export const getPackagesSelector = createSelector(
    [packagesStateSelector],
    (packages) => {
        try {
            return packages || []
        } catch (error) {
            return []
        }
    }
)
export const getPackageGroupSelectedSelector = createSelector(
    [packageStateSelector],
    (packages) => {
        try {
            return packages.group
        } catch (error) {
            return null
        }
    }
)
export const getPackageGroupsSelector = createSelector(
    [packagesStateSelector],
    (packages) => {
        try {
            if (packages) {
                packages = packages.map(group => {
                    const dateRanges = group.dateRanges.map(value => {
                        if (value.date_start) {
                            value.date_start = moment(value.date_start);
                        }
                        if (value.date_end) {
                            value.date_end = moment(value.date_end);
                        }
                        if (value.date_start && value.date_end) {
                            value.days = value.date_end.diff(value.date_start, 'days')
                            value.nights = value.days - 1
                        }
                        return value
                    })
                    group.dateRanges = dateRanges
                    group.date_range_index = null
                    return group
                })
            }
            let groups = _.groupBy(_.sortBy(packages, 'package_group.order'), 'package_group.code')
            return groups || []
        } catch (error) {
            return []
        }
    }
)
export const getPackageSelector = createSelector(
    [
        packageDetailStateSelector,
    ],
    (package_detail) => {
        try {
            let photos = (package_detail.photos || {})
            photos = photos.multimedia
            const dateRanges = package_detail.dateRanges.map(value => {
                if (value.date_start) {
                    value.date_start = moment(value.date_start);
                }
                if (value.date_end) {
                    value.date_end = moment(value.date_end);
                }
                if (value.date_start && value.date_end) {
                    value.days = value.date_end.diff(value.date_start, 'days')
                    value.nights = value.days - 1
                }
                return value
            })
            return {...package_detail, photos, dateRanges}
        } catch
            (error) {
            return []
        }
    }
)
export const getPricePackageSelector = createSelector(
    [
        packageProductsStateSelector,
        packagePriceBaseStateSelector,
    ],
    (products, price) => {
        try {
            let sum = 0
            if (products.length > 0) {
                sum = products.reduce((total, product) => {
                    return total + (product.default ? product.price : 0)
                }, price)
            }
            return sum
        } catch (error) {
            return []
        }
    }
)
export const getPackageProductsSelectedSelector = createSelector(
    [packageProductsStateSelector],
    (products) => {
        try {
            return _.filter(products, {'default': true})
        } catch (error) {
            return []
        }
    }
)
export const getPackageProductsByDefaultSelectedSelector = createSelector(
    [packageProductsStateSelector],
    (products) => {
        try {
            return _.filter(products, {'default': true}).map(product => product.code)
        } catch (error) {
            return []
        }
    }
)

export const getPackagesIsFulfilledSelector = createSelector(
    [packageStateSelector],
    ({isFulfilled}) => {
        try {
            return isFulfilled
        } catch (error) {
            return false
        }
    }
)