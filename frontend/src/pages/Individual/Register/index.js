import PropTypes from 'prop-types'
import React, {useEffect, useLayoutEffect, useState} from 'react'
import {Formik} from 'formik'
import {Col, Container, Form, Image, Row} from "react-bootstrap"
import schema from './_.schema'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from 'redux'
import {_preRegister, _setUserIsValidClear} from "../../../store/user"
import {getPackageSelector} from "../../../store/package/selectors"
import {getSelectedStore} from "../../../store/store/selectors"
import {jwtStateSelector, userSelector} from "../../../store/user/selectors"
import {getUser, signIn} from "../../../store/user/operators";
import {delJWT} from "../../../services/Base";
import FormControlIncrement from "../../../components/FormControlIncrement";
import withMenuRouter from "../../../layouts/withMenuRouter";
import IndividualButton from "../../../components/Individual/Button";
import UserMobileActivation from "../../../components/UserMobileActivation";
import calendar from '../../../layouts/IndividualTourism/img/calender.png'
import {_setParamsHeader} from "../../../store/core";
import {url_src} from "../../../store/core/_.utils";

const IndividualRegister = ({
                                store,
                                _preRegister,
                                user,
                                package_detail,
                                _getUser,
                                _setUserIsValidClear,
                                route,
                                jwt,
                                _register,
                                _setParamsHeader,
                            }) => {
    // Celular al que se envió el código; la sesión llega al validarlo.
    const [phoneToVerify, setPhoneToVerify] = useState(null)

    useEffect(() => {
        if (jwt) {
            _getUser(jwt)
                .then(res => console.log(res))
                .catch(err => {
                    console.log('Error', err)
                    _setUserIsValidClear()
                })
        } else {
            delJWT()
        }
    }, [jwt])

    useLayoutEffect(() => {
        window.scrollTo(0, 0)
    })

    useEffect(() => {
        _setParamsHeader({is_classic: true})
    }, [])

    if (phoneToVerify) {
        return <Container className={'bg-primary py-4'}>
            <Row className="justify-content-center">
                <Col xs={12} md={6}>
                    <p className="text-center text-white mb-4">Te enviamos un código por SMS
                        al {phoneToVerify.phone_code} {phoneToVerify.phone_number}</p>
                    <UserMobileActivation
                        phone_code={phoneToVerify.phone_code}
                        phone_number={phoneToVerify.phone_number}
                        onVerified={() => route('packageServices', [package_detail.code])}
                    />
                    <div className={"text-center my-3"}>
                        <IndividualButton type={'button'} variant={'link'} invert
                                          onClick={() => setPhoneToVerify(null)}>Cambiar mis datos</IndividualButton>
                    </div>
                </Col>
            </Row>
        </Container>
    }

    return <Formik
        validationSchema={schema}
        onSubmit={(data) => {
            let userComplete = {
                ...data, store_code: store.code, package_code: package_detail.code,
                qty: (data.adults + data.children),
                adults: data.adults,
                children: data.children,
                babies: data.babies,
                date_range_index: package_detail.date_range_index,
                date_start: package_detail.dateRanges[package_detail.date_range_index].date_start.format('YYYY/MM/DD'),
                date_end: package_detail.dateRanges[package_detail.date_range_index].date_end.format('YYYY/MM/DD'),
                date_range_id: package_detail.dateRanges[package_detail.date_range_index].id,
                province_code: 'buenos-aires',
                type: 'individual_tourism'
            }
            _preRegister(userComplete)
            _register(userComplete)
                .then(resp => {
                    if (resp.value.success) {
                        setPhoneToVerify({phone_code: data.phone_code, phone_number: data.phone_number})
                    }
                    return resp
                })
                .catch(err => {
                    debugger
                    return err
                });
            //route('packageServices', [package_detail.code])
        }}
        initialValues={{
            name: user.name || '',
            email: user.email || '',
            phone_code: user.phone_code || '',
            phone_number: user.phone_number || '',
            city: user.city || '',
            adults: 2,
            children: 0,
            babies: 0,
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
            return (
                <Form noValidate onSubmit={handleSubmit} autoComplete="off" className={'mt-n3'}>
                    <input autoComplete="false" type={'hidden'}/>
                    <Container className={'bg-primary py-4'}>
                        <Row>
                            <Col xs={12} sm={6}>
                                <Row className={'justify-content-center'}>
                                    <Col xs={12} sm={10}>
                                        <Row>
                                            <Col xs={12}>
                                                <Form.Group className={'mb-0'}>
                                                    <Form.Control
                                                        size={'sm'}
                                                        autoComplete={"new-password"}
                                                        type="text"
                                                        name="name"
                                                        value={values.name}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        isInvalid={errors.name}
                                                    />
                                                    <Form.Control.Feedback type="invalid">
                                                        {errors.name}
                                                    </Form.Control.Feedback>
                                                    <Form.Label className={"text-uppercase mb-0"}>Nombre y
                                                        apellido</Form.Label>
                                                </Form.Group>
                                                <Form.Group className={'mb-0'}>
                                                    <Form.Control
                                                        size={'sm'}
                                                        autoComplete={"new-password"}
                                                        type="email"
                                                        name="email"
                                                        value={values.email}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        isInvalid={errors.email}
                                                    />
                                                    <Form.Control.Feedback type="invalid">
                                                        {errors.email}
                                                    </Form.Control.Feedback>
                                                    <Form.Label className={"text-uppercase mb-0"}>Email</Form.Label>
                                                </Form.Group>
                                                <Form.Group>
                                                    <Form.Control
                                                        type="number"
                                                        autoComplete={"new-password"}
                                                        size={'sm'}
                                                        name="phone_code"
                                                        value={values.phone_code}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        isInvalid={errors.phone_code}
                                                    />
                                                    <Form.Control.Feedback type="invalid">
                                                        {errors.phone_code}
                                                    </Form.Control.Feedback>
                                                    <Form.Label className={"text-uppercase mb-0"}>Código de
                                                        área</Form.Label>
                                                    <small className="form-text text-info">
                                                        Sin 0
                                                    </small>
                                                </Form.Group>
                                                <Form.Group>
                                                    <Form.Control
                                                        type="number"
                                                        autoComplete={"new-password"}
                                                        size={'sm'}
                                                        name="phone_number"
                                                        value={values.phone_number}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        isInvalid={errors.phone_number}
                                                    />
                                                    <Form.Control.Feedback type="invalid">
                                                        {errors.phone_number}
                                                    </Form.Control.Feedback>
                                                    <Form.Label className={"text-uppercase mb-0"}>Número de
                                                        teléfono</Form.Label>
                                                    <small className="form-text text-info">
                                                        Sin 15
                                                    </small>
                                                </Form.Group>
                                                <Form.Group className={'mb-0'}>
                                                    <Form.Control
                                                        size={'sm'}
                                                        autoComplete={"new-password"}
                                                        type="text"
                                                        name="city"
                                                        value={values.city}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        isInvalid={errors.city}
                                                    />
                                                    <Form.Label className={"text-uppercase mb-0"}>Localidad</Form.Label>
                                                    <Form.Control.Feedback type="invalid">
                                                        {errors.city}
                                                    </Form.Control.Feedback>
                                                </Form.Group>
                                                <p className={"text-center text-white text-uppercase font-size-sm"}>Pasajeros</p>
                                            </Col>
                                        </Row>
                                        <Row className="justify-content-center">
                                            <Col xs={4}>
                                                <label
                                                    className={'d-block text-center text-white m-0 font-size-xs'}>Adultos</label>
                                                <small className={'d-block text-center text-info font-size-xs'}>desde 12
                                                    años</small>
                                            </Col>
                                            <Col xs={4}>
                                                <label
                                                    className={'d-block text-center text-white m-0 font-size-xs'}>Niños</label>
                                                <small className={'d-block text-center text-info font-size-xs'}>de 2 a
                                                    11
                                                    años</small>
                                            </Col>
                                            <Col xs={4}>
                                                <label
                                                    className={'d-block text-center text-white m-0 font-size-xs'}>Bebes</label>
                                                <small className={'d-block text-center text-info font-size-xs'}>de 0 a 2
                                                    años
                                                    sin
                                                    costo</small>
                                            </Col>
                                        </Row>
                                        <Row className="justify-content-center">
                                            <Col xs={4} className={'d-flex mr-0 pr-0'}>
                                                <FormControlIncrement
                                                    name="adults"
                                                    maxValue={8}
                                                    minValue={1}
                                                    value={values.adults}
                                                    onChange={(value) => setFieldValue("adults", value)}
                                                    isInvalid={errors.adults}
                                                />
                                            </Col>
                                            <Col xs={4} className={'d-flex mx-0 px-0'}>
                                                <FormControlIncrement
                                                    name="children"
                                                    maxValue={8}
                                                    value={values.children}
                                                    onChange={(value) => setFieldValue("children", value)}
                                                    isInvalid={errors.children}
                                                />
                                            </Col>
                                            <Col xs={4} className={'d-flex ml-0 pl-0'}>
                                                <FormControlIncrement
                                                    name="babies"
                                                    maxValue={8}
                                                    value={values.babies}
                                                    onChange={(value) => setFieldValue("babies", value)}
                                                    isInvalid={errors.babies}
                                                />
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                            </Col>
                            <Col xs={12} sm={5} className={'d-none d-sm-block p-0'}>
                                <Image fluid src={url_src('img/register_img.png')}/>
                            </Col>
                            <Col xs sm={1}></Col>
                        </Row>
                    </Container>
                    <Container>
                        <Row className="justify-content-center d-block d-sm-none">
                            <Col xs={10} md={6}>
                                <div className={"text-left my-3 text-uppercase d-flex"}>
                                    <Image src={calendar} height={42} width={42}/>
                                    <div className={'ml-2 align-self-center'}>
                                        <p className={'m-0 font-size-sm'}>Fecha de
                                            salida {package_detail.dateRanges && package_detail.dateRanges[package_detail.date_range_index] && package_detail.dateRanges[package_detail.date_range_index].date_start ? package_detail.dateRanges[package_detail.date_range_index].date_start.format('DD/MM/YYYY') : null} desde
                                            buenos aires</p>
                                    </div>
                                </div>
                            </Col>
                        </Row>
                        <Row className="justify-content-center mt-sm-4">
                            <Col xs={8} md={3}>
                                <div className={"text-center mb-3"}>
                                    <IndividualButton block type={'submit'}>continuar</IndividualButton>
                                </div>
                                <div className={"text-center mb-3"}>
                                    <IndividualButton block variant={'link'}
                                                      to={() => route('selectPackages', [store.code])}>Volver</IndividualButton>
                                </div>
                            </Col>
                        </Row>
                    </Container>
                </Form>
            )
        }}
    </Formik>
}

const mapStateToProps = state => {
    return {
        store: getSelectedStore(state),
        user: userSelector(state),
        package_detail: getPackageSelector(state),
        jwt: jwtStateSelector(state),
    }
}
const mapDispatchToProps = dispatch => bindActionCreators({
    _preRegister: _preRegister,
    _getUser: getUser,
    _setUserIsValidClear: _setUserIsValidClear,
    _register: signIn,
    _setParamsHeader: _setParamsHeader,
}, dispatch)

export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps))
(IndividualRegister)

IndividualRegister.propTypes = {
    store: PropTypes.object.isRequired,

}
IndividualRegister.defaultProps = {}
