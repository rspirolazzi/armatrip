import React, {useLayoutEffect, useState} from 'react'
import {Formik} from 'formik'
import {Alert, Button, Col, Form, Row} from "react-bootstrap"
import schema from './_.schema'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from 'redux'
import {payment, postOrder} from "../../../store/order/operators"
import {getPackageProductsSelectedSelector, getPackageSelector} from "../../../store/package/selectors"
import {_Utils, formatMoney, url_src} from "../../../store/core/_.utils";
import {MercadoPago, TYPE_ERRORS} from '../../../common/mercadopago'
import {configurationStateSelector} from '../../../store/configuration/selectors'
import ListOfProducts from "../../../components/ListOfProducts";
import PropTypes from "prop-types";
import IndividualButton from "../../../components/Individual/Button";
import withMenuRouter from "../../../layouts/withMenuRouter";
import IndividualListOfProducts from "../../../components/Individual/IndividualListOfProducts";

const IndividualPayment = ({
                               _sendPayment,
                               _postOrder,
                               history,
                               package_detail,
                               products,
                               route,
                               configuration,
                           }) => {


    let MERCADO_PAGO = null

    const [errorMessage, setErrorMessage] = useState('')
    const [successMessage, setSuccessMessage] = useState('')
    const [showDetail, setShowDetail] = useState(false)
    const [mercadoPagoIsEnabled, setMercadoPagoIsEnabled] = useState(true)
    const [imageCard, setImageCard] = useState('')

    try {
        MERCADO_PAGO = new MercadoPago(_Utils().API.MERCADOPAGO_KEY)
    } catch (e) {
        setMercadoPagoIsEnabled(false)
    }
    useLayoutEffect(() => {
        window.scrollTo(0, 0)
    })


    return <Formik
        validationSchema={schema}
        onSubmit={(data) => {
            setErrorMessage('');
            setSuccessMessage('');

            const values = {
                ...data,
                cardExpirationMonth: data.expiration.split('/')[0],
                cardExpirationYear: data.expiration.split('/')[1]
            }
            const sdkResponseHandler = (status, response) => {
                if (status !== 200 && status !== 201) {
                    setErrorMessage('Ocurrio un error/es al procesar el pago: ' + MERCADO_PAGO.getMessageFromResponse(response).map(cause => cause).join(','))

                } else {
                    const dataToPostOrder = {
                        payment_method_id: values.paymentMethodId,
                        amount: values.amount,
                        mp_notification_id: response.id,
                        code: package_detail.code,
                        products: products.map(product => product.code)
                    }
                    _sendPayment(dataToPostOrder)
                        .then(resp => {
                            _postOrder(dataToPostOrder)
                                .then(resp => {
                                    if (resp.success && resp.message) {
                                        setSuccessMessage(resp.message)
                                    } else {
                                        setSuccessMessage('La reserva se realizo con éxito')
                                    }
                                    setTimeout(function () {
                                        route('checkout')
                                    }, 2500);
                                    return resp
                                })
                            return resp
                        })
                        .catch(err => {
                            if (!err.success && err.message) {
                                setErrorMessage('Ocurrio un error/es al procesar el pago: ' + err.message)
                            }
                        })
                }
                MERCADO_PAGO.clearSession()
            }
            MERCADO_PAGO.createToken(values, sdkResponseHandler)
        }}
        initialValues={{
            cardNumber: "",
            expiration: '',
            cardholderName: "",
            cardExpirationMonth: "01",
            cardExpirationYear: (new Date()).getFullYear(),
            securityCode: "",
            docType: "DNI",
            docNumber: "",
            paymentMethodId: '',
            amount: package_detail.amount_mp || 0
        }}>
        {({
              handleSubmit,
              handleChange,
              handleBlur,
              values,
              touched,
              isValid,
              errors,
              setFieldValue,
          }) => {

            const setFieldValueForPaymentMethodId = resp => {
                if (resp) {
                    setFieldValue('paymentMethodId', resp.id)
                    setImageCard(resp.thumbnail)
                } else {
                    setFieldValue('paymentMethodId', '')
                    setImageCard('')
                }
            }

            return (
                <>
                    <Form id="pay" noValidate onSubmit={handleSubmit}>
                        <Row className="mx-4 justify-content-center">
                            <Col xs={12} md={6}>
                                <p className={"text-center black text-info text-uppercase "}>Estas apunto de pagar
                                    ${formatMoney(package_detail.amount_mp, 0)} como reserva para el
                                    viaje {package_detail.name}</p>
                            </Col>
                        </Row>
                        {errorMessage !== '' && <Alert dismissible onClose={() => setErrorMessage('')}
                                                       variant='danger'>{errorMessage}</Alert>}
                        {successMessage !== '' &&
                        <Alert variant='success'><i className='fa fa-spinner fa-spin'></i>{successMessage}</Alert>}
                        <div className={'bg-primary pt-2'}>
                            <Row className="justify-content-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="number"
                                                name="cardNumber"
                                                maxLength={16}
                                                value={values.cardNumber}
                                                data-checkout="cardNumber"
                                                onChange={MERCADO_PAGO.handleForGuessingPaymentMethod(handleChange, setFieldValueForPaymentMethodId)}
                                                onBlur={MERCADO_PAGO.handleForGuessingPaymentMethod(handleBlur, setFieldValueForPaymentMethodId)}
                                                isValid={touched.cardNumber && !errors.cardNumber}
                                                isInvalid={!!errors.cardNumber}
                                            />
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="hidden"
                                                readOnly
                                                name="paymentMethodId"
                                                maxLength={16}
                                                value={values.paymentMethodId}
                                                data-checkout="paymentMethodId"
                                                isValid={touched.paymentMethodId && !errors.paymentMethodId}
                                                isInvalid={!!errors.paymentMethodId}
                                            />
                                            <Form.Label className={"text-uppercase"}>Número de tarjeta</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.paymentMethodId}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                            <Row className="justify-content-center">
                                <Col xs={6} md={3}>
                                    <Form.Row className={'ml-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="expiration"
                                                maxLength={16}
                                                value={values.expiration}
                                                data-checkout="expiration"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.expiration && !errors.expiration}
                                                isInvalid={!!errors.expiration}
                                            />
                                            <Form.Label className={"text-uppercase"}>Fecha de exp (MM/YY)</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.expiration}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                                <Col xs={6} md={3}>
                                    <Form.Row className={'mr-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="password"
                                                name="securityCode"
                                                maxLength={16}
                                                value={values.securityCode}
                                                data-checkout="securityCode"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.securityCode && !errors.securityCode}
                                                isInvalid={!!errors.securityCode}
                                            />
                                            <Form.Label className={"text-uppercase"}>Cod Seg</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.securityCode}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                            <Row className="justify-content-center">
                                <Col xs={6} md={3}>
                                    <Form.Row className={'ml-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                as="select"

                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                name="docType"
                                                maxLength={16}
                                                value={values.docType}
                                                data-checkout="docType"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.docType && !errors.docType}
                                                isInvalid={!!errors.docType}
                                            ></Form.Control>
                                            <Form.Label className={"text-uppercase"}>Tipo</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.docType}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                                <Col xs={6} md={3}>
                                    <Form.Row className={'mr-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="docNumber"
                                                maxLength={16}
                                                value={values.docNumber}
                                                data-checkout="docNumber"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.docNumber && !errors.docNumber}
                                                isInvalid={!!errors.docNumber}
                                            />
                                            <Form.Label className={"text-uppercase"}>Número</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.docNumber}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                            <Row className="justify-content-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            {configuration.payment.isTest ? <Form.Control
                                                size={'sm'}
                                                as='select'
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="cardholderName"
                                                value={values.cardholderName}
                                                data-checkout="cardholderName"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.cardholderName && !errors.cardholderName}
                                                isInvalid={!!errors.cardholderName}
                                            >
                                                <option value={''}></option>
                                                {Object.keys(TYPE_ERRORS).map(k => <option key={k}
                                                                                           value={k}>{TYPE_ERRORS[k]}</option>)}
                                            </Form.Control> : <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="cardholderName"
                                                value={values.cardholderName}
                                                data-checkout="cardholderName"
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.cardholderName && !errors.cardholderName}
                                                isInvalid={!!errors.cardholderName}
                                            />}
                                            <Form.Label className={"text-uppercase"}>Nombre del tiular</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.cardholderName}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                        </div>
                        <Row className={'justify-content-center'}>
                            <Col xs={12} sm={6}>
                                <div className={'mx-4 mb-2 text-center bg-white border border-white rounded-lg'}>
                                    <div className={'py-1 px-2'}>
                                        <img src={url_src('/img/credit_card_icons.png')} width={'100%'}/>
                                    </div>
                                </div>
                            </Col>
                        </Row>
                        <Row className="justify-content-center mt-4">
                            <Col xs={6} sm={2} className={'pr-1'}>
                                <IndividualButton block variant={'secondary'} invert
                                                  onClick={() => route('packageDetail', [package_detail.code])}>Volver</IndividualButton>

                            </Col>
                            <Col xs={6} sm={2} className={'p-1'}>
                                <IndividualButton block disabled={successMessage !== ''}
                                                  type={'submit'}>OK</IndividualButton>

                            </Col>
                        </Row>
                    </Form>
                    <Row id={'detail_reservation'} className="justify-content-center m-2">
                        <Col xs={12} md={6}>
                            <div className={'bg-light-2 border rounded m-2'}>
                                <h5 className={"d-block d-sm-none text-center black text-uppercase"}>Detalle de tu reserva</h5>
                                <Button as={'h5'} variant={'link'} onClick={()=>setShowDetail(!showDetail)} className={"d-none d-sm-block text-center text-uppercase"}>
                                    ver detalle de reserva <i className={'fa fa-angle-down'}></i>
                                </Button>
                                <div className={showDetail ?'': 'd-block d-sm-none'}>
                                    <div className={'bg-white mx-1 px-1'}>
                                        <IndividualListOfProducts products={products}/>
                                    </div>
                                </div>
                            </div>
                        </Col>
                    </Row>
                </>
            )
        }}
    </Formik>
}

const mapStateToProps = state => {
    return {
        products: getPackageProductsSelectedSelector(state),
        package_detail: getPackageSelector(state),
        configuration: configurationStateSelector(state),
    }
}
const mapDispatchToProps = dispatch => bindActionCreators({
    _sendPayment: payment,
    _postOrder: postOrder,
}, dispatch)

export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps),
)(IndividualPayment)

IndividualPayment.propTypes = {
    _sendPayment: PropTypes.func.isRequired,
    _postOrder: PropTypes.func.isRequired,
    history: PropTypes.object.isRequired,
    package_detail: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired,
    products: PropTypes.array.isRequired,
    configuration: PropTypes.object.isRequired
}
IndividualPayment.defaultProps = {}
