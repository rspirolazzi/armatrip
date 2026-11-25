import React from "react"
import PropTypes from 'prop-types'
import {groupProductsByCategory} from "../../../store/product/_.utils"
import Categories from "./Categories/index"

const ProductServices = ({code, name, products, onClickSelectProduct, actions, className, handleIsDirty}) => {

    return <div className={className + " mt30"}>
        <h5 className="font-weight-bold none">{name}</h5>
        {groupProductsByCategory(products).map((category, i) => <Categories
            handleIsDirty={(value) => handleIsDirty(category.name, value)}
            key={i}
            title={category.name}
            items={category.products}
            multiple={category.multiple}
            onClickSelectProduct={onClickSelectProduct}
            actions={actions}
        />)}
    </div>
}

ProductServices.propTypes = {
    category: PropTypes.object,
    products: PropTypes.oneOfType([PropTypes.array, PropTypes.object]),
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    icon: PropTypes.string,
    image: PropTypes.string,
    max: PropTypes.number,
    min: PropTypes.number,
    name: PropTypes.string.isRequired,
    price: PropTypes.number,
    onClickSelectProduct: PropTypes.func.isRequired,
    handleIsDirty: PropTypes.func,
}

ProductServices.defaultProps = {
    category: {
        name: '',
        order: '',
    },
    products: [],
    handleIsDirty: () => {
    }
}

export default ProductServices