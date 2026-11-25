import PropTypes from 'prop-types'
import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getPackage} from "../../../store/package/operations"
import {getPackageSelector} from "../../../store/package/selectors"
import ProductDetailServices from '../../../components/Cart/ProductServices'
import {_setSelectedProduct} from "../../../store/package"
import {getActionNotificationsSelector} from "../../../store/notification/selectors"
import {Col, Row} from "react-bootstrap"
import IndividualButton from "../../../components/Individual/Button";
import withMenuRouter from "../../../layouts/withMenuRouter";

const Services = ({package_detail, match, getPackage, dispatchSelectProduct, actions, route}) => {
    useEffect(() => {
        componentWillMount()
    }, [])

    const componentWillMount = () => {
        if (package_detail === undefined) {
            getPackage(match.params.code)
        }
    }
    return <div>
        <h5 className={"black text-primary text-uppercase"}>Agregar + servicios</h5>
        <ProductDetailServices {...package_detail} actions={actions}
                               onClickSelectProduct={dispatchSelectProduct} handleIsDirty={(code, value)=>console.log('Cambios en:', code, value)}/>
        <Row className={"justify-content-center text-center mt30"}>
            <Col xs={6} sm={2} className={'pr-1'}>
                <IndividualButton block variant={'secondary'} invert
                                  onClick={() => route('packageServices', [package_detail.code])}>Volver</IndividualButton>
            </Col>
            <Col xs={6} sm={2} className={'pl-1'}>
                <IndividualButton block variant={'success'}
                                  onClick={() => route('packageSummary', [package_detail.code])}>confirmar</IndividualButton>

            </Col>
        </Row>
    </div>
}

Services.propTypes = {
    package_detail: PropTypes.array.isRequired
}

Services.defaultProps = {
    package_detail: {}
}
const mapStateToProps = (state, props) => ({
    package_detail: getPackageSelector(state, props),
    actions: getActionNotificationsSelector(state, props),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    getPackage: getPackage,
    dispatchSelectProduct: _setSelectedProduct,
}, dispatch)


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps)
)(Services)
