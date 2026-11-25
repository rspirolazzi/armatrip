import _ from 'lodash'

export const transformProducts = (products) => {
    return _.groupBy(products, 'category.id')
}
export const transformProductsCategory = (products, excluded_children = []) => {
    let resultProducts = [...products]
    if (excluded_children !== null && Array.isArray(excluded_children)) {
        resultProducts = _.differenceBy(products, excluded_children, 'id')
    }
    const productsGrouped = _.groupBy(resultProducts, 'category.id')
    return Object.keys(productsGrouped).map(key => {
        const {category} = productsGrouped[key][0]
        return {category, children: productsGrouped[key]}
    })
}

export const orderProductsAsc = (products) => {
    return _.orderBy(products, 'order')
}
export const groupProductsByCategory = (products) => {
    const productsGrouped = _.groupBy(_.orderBy(products, 'category.order'), 'category.id')
    return Object.keys(productsGrouped).map(key => {
        const {category} = productsGrouped[key][0]
        return {...category, products: _.sortBy(productsGrouped[key], 'order')}
    })
}