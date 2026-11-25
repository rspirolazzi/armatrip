import PropTypes from 'prop-types'
import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {getPackage} from "../../../store/package/operations"
import {getPackageSelector} from "../../../store/package/selectors"
import ProductDetailServices from '../../../components/Cart/ProductServices'
import {_setSelectedProduct} from "../../../store/package"
import {getActionNotificationsSelector} from "../../../store/notification/selectors"
import {Col, Row} from "react-bootstrap"
import PurpleLink from "../../../components/PurpleLink";
import {Link} from "react-router-dom";
import {route} from "../../../common/utils"

const Services = ({product, match, dispatchLoadDetail, dispatchSelectProduct, actions}) => {
    useEffect(() => {
        componentWillMount()
    }, [])

    const componentWillMount = () => {
        if (product === undefined) {
            dispatchLoadDetail(match.params.code)
        }
    }
    return <><Row className="justify-content-md-center">
        <Col xs={12} md={6}>
            <ProductDetailServices className={'mx-4'} {...product} actions={actions}
                                   onClickSelectProduct={dispatchSelectProduct}/>
        </Col>
    </Row>
        <div className={"text-center mt30"}>
            <div className="gray book font-size-12 mb15">CONFIRMAR</div>
            <PurpleLink to={route('packageConfirm', [match.params.code])}>OK</PurpleLink>
        </div>
        <div className={"text-center mt15"}>
            <Link to={route('packageDetail', [match.params.code])} className="">Volver</Link>
        </div>
    </>
}

Services.propTypes = {
    product: PropTypes.array.isRequired
}

Services.defaultProps = {
    product: {}
}
const mapStateToProps = (state, props) => ({
    product: getPackageSelector(state, props),
    actions: getActionNotificationsSelector(state, props),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    dispatchLoadDetail: getPackage,
    dispatchSelectProduct: _setSelectedProduct,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Services)