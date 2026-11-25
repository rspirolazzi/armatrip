import {createSelector} from 'reselect';

const productsStateSelector = state => state.product.all
const productStateSelector = state => state.product.detail
const productCodePropSelector = (_, prop) => prop.match.params.code

export const getProductsSelector = createSelector(
    [productsStateSelector],
    (products) => {
        try {
            return products
        } catch (error) {
            return []
        }
    }
)
export const getProductSelector = createSelector(
    [
        productStateSelector
    ],
    (product) => {
        try {
            return product
        } catch (error) {
            return []
        }
    }
)