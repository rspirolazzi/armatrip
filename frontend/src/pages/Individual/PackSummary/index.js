import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {
    getPackageProductsByDefaultSelectedSelector,
    getPackageProductsSelectedSelector,
    getPackageSelector
} from "../../../store/package/selectors"
import PackSummary from '../../../components/Individual/PackSummary'
import {getPackageDetail} from "../../../store/package/operations"
import {getSelectedStore} from "../../../store/store/selectors"
//import '../styles.css'
//import imgHeader from "../img/header_20200705-01.png";
import withMenuRouter from "../../../layouts/withMenuRouter";
import {Col, Row} from "react-bootstrap";
import IndividualButton from "../../../components/Individual/Button";
import Price from "../../../components/Price";
import {userSelector} from "../../../store/user/selectors";
import {isMobile} from "../../../common/utils";

const IndividualPackConfirm = ({
                                   package_detail,
                                   store,
                                   products,
                                   productsSelected,
                                   _setLoadDetail,
                                   route,
                                   user
                               }) => {
    const [loaded, setLoaded] = useState(false)
    useEffect(() => {
        if (!loaded) {
            _setLoadDetail({codes: productsSelected})
            setLoaded(true)
        }
    });
    return <>
        <Row className={'justify-content-center'}>
            <Col xs={12} sm={4}>

                <h5 className="black text-center text-uppercase">Hola {user.name}</h5>
                <p className="text-center mb-0">Contenido del viaje</p>
                <h5 className="black text-center text-uppercase">{package_detail.name}</h5>
                <PackSummary
                    className={'content-detail mx-4'}
                    alignText={isMobile() ? 'text-left' : 'text-center'}
                    {...package_detail}
                    products={products}
                    store={store}
                    getDetailPackage={() => _setLoadDetail({codes: productsSelected})}
                />
                <p className={"mb-0 book line1 text-center"}>{package_detail.installment} {package_detail.installment > 1 ? 'cuotas' : 'cuota'} de </p>
                <h3 className={"mb-0 mt-3 book line1 text-center"}>
            <span className={'font-size-36 bg-brand rounded-pill px-4 py-2'}>
            <Price className={"bold"} currency={store.currency.symbol}
                   value={package_detail.price_by_passagers_tender}/>
            </span>
                </h3>
                <p className={"book line1 font-size-14 text-brand mb-0  text-center"}>Precio del paquete por persona: <Price
                    className={"bold font-size-16"}
                    currency={store.currency.symbol}
                    value={package_detail.price_by_passagers}/></p>
                {store && store.options && store.options.currency_message &&
                <p className={"book font-size-20 text-muted my-2"}>{store.options.currency_message}</p>}

                <Row className={"mt30 line1"}>
                    <Col xs={6} className={"text-right child-plus"}>
                        <IndividualButton block variant={'info'} invert
                                          onClick={() => route('packageServicesEdit', [package_detail.code])}>
                            + servicios
                        </IndividualButton>
                    </Col>
                    <Col xs={6} className={"text-left"}>
                        <IndividualButton block onClick={() => route('prePay')}>
                            confirmar
                        </IndividualButton>
                    </Col>
                </Row>
                <Row className={"mt30 line1"}>
                    <Col xs={12} className={"text-center"}>
                        <IndividualButton variant={'link'}
                                          onClick={() => route('packageServices', [package_detail.code])}>Volver</IndividualButton>
                    </Col>
                </Row>
            </Col>
        </Row>
    </>
}

IndividualPackConfirm.propTypes = {
    package_detail: PropTypes.array.isRequired
}

IndividualPackConfirm.defaultProps = {
    package_detail: {}
}
const mapStateToProps = state => ({
    user: userSelector(state),
    package_detail: getPackageSelector(state),
    products: getPackageProductsSelectedSelector(state),
    productsSelected: getPackageProductsByDefaultSelectedSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _setLoadDetail: getPackageDetail,
}, dispatch)


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps))
(IndividualPackConfirm)
