import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {
    getPackageProductsByDefaultSelectedSelector,
    getPackageProductsSelectedSelector,
    getPackageSelector
} from "../../../store/package/selectors"
import ProductConfirm from '../../../components/Cart/Summary'
import {getPackageDetail} from "../../../store/package/operations"
import {Col, Row} from "react-bootstrap"
import {getSelectedStore} from "../../../store/store/selectors"
import '../styles.css'
import imgHeader from "../img/header_20200705-01.png";
import FloatButtonAction from "../../../components/FloatButtonAction";

const Confirm = ({product, store, products, productsSelected, _setLoadDetail}) => {
    const [loaded, setLoaded] = useState(false)
    useEffect(() => {
        if (!loaded) {
            _setLoadDetail({codes: productsSelected})
            setLoaded(true)
        }
    });
    return <>
        <div className={'content-image d-md-none'}>
            <img src={imgHeader} className={'img-fluid'}/>
        </div>
        <div className={'content-image d-none d-md-block'}>
            <Row className={'align-items-center'}>
                <Col xs={4}>
                    <img src={imgHeader} className={'img-fluid'}/>
                </Col>
                <Col xs={4}>
                    <h3 className="text-center text-muted font-size-36 black text-uppercase mx-4 my-0">{product.name}</h3>
                </Col>
            </Row>
        </div>
        <ProductConfirm
            className={'content-detail mx-4'}
            {...product}
            products={products}
            store={store}
            getDetailPackage={() => _setLoadDetail({codes: productsSelected})}
        />
        <FloatButtonAction/>
    </>
}

Confirm.propTypes = {
    product: PropTypes.array.isRequired
}

Confirm.defaultProps = {
    product: {}
}
const mapStateToProps = state => ({
    product: getPackageSelector(state),
    products: getPackageProductsSelectedSelector(state),
    productsSelected: getPackageProductsByDefaultSelectedSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _setLoadDetail: getPackageDetail,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Confirm)
