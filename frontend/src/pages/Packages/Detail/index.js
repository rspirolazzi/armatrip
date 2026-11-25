import PropTypes from 'prop-types'
import React, {Component} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {getPackageDetail} from "../../../store/package/operations"
import {
    getPackageProductsSelectedSelector,
    getPackageSelector,
    getPricePackageSelector
} from "../../../store/package/selectors"
import ProductDetail from '../../../components/Cart/ProductDetail'
import {getSelectedStore} from "../../../store/store/selectors"
import {userSelector} from "../../../store/user/selectors"
import {Col, Row} from "react-bootstrap"
import FloatButtonAction from "../../../components/FloatButtonAction";

import '../styles.css'
import imgHeader from '../img/header_20200705-01.png'

class Detail extends Component {

    componentWillMount() {
        this.props.dispatchLoadDetail()
    }

    render() {
        const {
            product,
            products,
            store,
            user,
        } = this.props
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
                        <h3 className="text-center text-muted font-size-24 mx-4 my-0">Hola {user.name}!</h3>
                        <h3 className="text-center text-muted font-size-36 black text-uppercase mx-4 my-0">{product.name}</h3>
                    </Col>
                </Row>
            </div>
            <ProductDetail
                className={'content-detail mx-4'}
                {...product}
                products={products}
                store={store}
                user={user}
                getDetailPackage={this.props.dispatchLoadDetail}
            />
            <FloatButtonAction/>

        </>
    }
}

Detail.propTypes = {
    product: PropTypes.object.isRequired,
}

Detail.defaultProps = {
    product: {}
}
const mapStateToProps = state => ({
    user: userSelector(state),
    product: getPackageSelector(state),
    products: getPackageProductsSelectedSelector(state),
    total: getPricePackageSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = dispatch => bindActionCreators({
    dispatchLoadDetail: getPackageDetail,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Detail)
