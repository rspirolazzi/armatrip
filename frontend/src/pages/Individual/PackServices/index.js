import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getPackageDetail} from "../../../store/package/operations"
import {
    getPackageProductsSelectedSelector,
    getPackageSelector,
    getPricePackageSelector
} from "../../../store/package/selectors"
import {Col, Container, Row} from "react-bootstrap"
import {userSelector} from "../../../store/user/selectors";
import {getSelectedStore} from "../../../store/store/selectors";
import Price from "../../../components/Price";
import withMenuRouter from "../../../layouts/withMenuRouter";
import IndividualButton from "../../../components/Individual/Button";
import IndividualListOfProducts from "../../../components/Individual/IndividualListOfProducts";
import {isMobile} from "../../../common/utils";

const IndividualPackServices = ({
                                    package_detail,
                                    services,
                                    store,
                                    user,
                                    _setSelectedProduct,
                                    getPackageDetail,
                                    route
                                }) => {
    useEffect(() => {
        componentWillMount()
    }, [])

    const [installments, setInstallments] = useState(package_detail.installment)

    const componentWillMount = () => {
        if (package_detail !== undefined) {
            getPackageDetail()
        }
    }
    return <div className={"text-center"}>
        <Row className={'justify-content-center'}>
            <Col xs={12} sm={4}>
                <h5 className="black text-center text-uppercase">Hola {user.name}</h5>
                <p className="text-center mb-0">Contenido del viaje</p>
                <h5 className="black text-center text-uppercase">{package_detail.name}</h5>
                <IndividualListOfProducts products={services} alignText={isMobile() ? 'text-left' : 'text-center'}/>
                <p className={"mt-1 book line1 text-center"}>{package_detail.installment} {package_detail.installment > 1 ? 'cuotas' : 'cuota'} de </p>
                <h3 className={"mt-1 mt-3 book line1 text-center"}>
            <span className={'font-size-36 px-4 py-2'}>
            <Price className={"bold"} currency={store.currency.symbol}
                   value={package_detail.price_by_passagers_tender}/>
            </span>
                </h3>
                <p className={"book line1 font-size-14 text-success mb-2 text-center"}>precio del paquete por persona: <Price
                    className={"bold font-size-16"} currency={store.currency.symbol}
                    value={package_detail.price_by_passagers}/>
                </p>
                {store && store.options && store.options.currency_message &&
                <p className={"book font-size-20 text-muted mb-2"}>{store.options.currency_message}</p>}
                <Container>
                    <Row>
                        <Col xs={6} className={"text-center pr-1"}>
                            <IndividualButton block variant={'info'} invert
                                              onClick={() => route('packageServicesEdit', [package_detail.code])}>+
                                Servicios</IndividualButton>
                        </Col>
                        <Col xs={6} className={"text-center pl-1"}>
                            <IndividualButton block variant={'success'}
                                              onClick={() => route('prePay')}>Continuar</IndividualButton>
                        </Col>
                        <Col xs={12} className={"text-center"}>
                            <IndividualButton variant={'link'}
                                              onClick={() => route('register')}>volver</IndividualButton>
                        </Col>
                    </Row>
                </Container>
            </Col>
        </Row>
    </div>
}

IndividualPackServices.propTypes = {
    package_detail: PropTypes.array.isRequired
}

IndividualPackServices.defaultProps = {
    package_detail: {}
}
const mapStateToProps = state => ({
    user: userSelector(state),
    package_detail: getPackageSelector(state),
    services: getPackageProductsSelectedSelector(state),
    total: getPricePackageSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = dispatch => bindActionCreators({
    getPackageDetail: getPackageDetail,
}, dispatch)


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps))
(IndividualPackServices)