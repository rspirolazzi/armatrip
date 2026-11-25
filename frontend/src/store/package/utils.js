import _ from 'lodash'

export const getSelectedProductsFromPackages = (productsPackage)=>{
    let products = _.orderBy(productsPackage, 'category.order')
    products = _.filter(products, {'default':true})
    return products
}
export const transformGroupByCategory=products=>{
    const categories = _.groupBy(_.sortBy(products, 'category.order'),'category.id')
    const new_result = Object.keys(categories).map(category_id=>{
        const category = categories[category_id][0].category
        return {...category, products: categories[category_id]}
    })
    return _.sortBy(new_result, 'order')
}