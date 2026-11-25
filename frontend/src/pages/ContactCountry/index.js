import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {Formik} from 'formik'
import {Button, Col, Form, Row} from "react-bootstrap"
import schema from './_.schema'
import {withRouter} from "react-router"
import {connect} from 'react-redux'
import {bindActionCreators} from 'redux'
import {_preRegister} from "../../store/user"
import {getPackagesSelector} from "../../store/package/selectors"
import {getSelectedStore} from "../../store/store/selectors"
import {getPackages} from "../../store/package/operations"
import {sendContactCountry, signIn} from "../../store/user/operators"
import {_setPackageCode} from "../../store/package"
import {userSelector} from "../../store/user/selectors"

const RegisterComplete = (props) => {

    const [loaded, setLoaded] = useState(false)

    useEffect(() => {
        if (!loaded) {
            props.dispatchLoadPackages(props.store.code)
            setLoaded(true)
        }
    })


    return <Row className="justify-content-md-center">
        <Col xs={12} md={6}>
            <Formik
                validationSchema={schema}
                onSubmit={(data) => {
                    /*let userComplete = {...data, verification_type: 'sms', store_code: props.store.code}
                    props.dispatchPreRegister(userComplete)
                    props.dispatchRegister(userComplete)
                        .then(resp => {
                            //props.history.push('/packages')
                            return resp
                        })
                    props.dispatchSelectPackage(data.package_code)*/
                    let contactComplete = {
                        ...data,
                        store_code: props.store.code,
                        name: 'Contacto Pais',
                        comment: 'Contacto por cotizacion al exterior'
                    }
                    props.dispatchContactCountry(contactComplete)
                        .then(resp => {
                            props.history.push('/thanks')
                            return resp
                        })
                }}
                initialValues={{
                    phone_code: props.user.phone_code || '',
                    phone_number: props.user.phone_number || '',
                }}>
                {({
                      handleSubmit,
                      handleChange,
                      handleBlur,
                      values,
                      touched,
                      errors,
                      setFieldValue,
                  }) => {

                    return (
                        <Form noValidate onSubmit={handleSubmit}>
                            <h2 className="text-brand font-size-18 bold mt100 text-center">Envianos un telèfono y a la
                                brevedad nos estaremos comunicando para asesorarte acerca del viaje desde tu pais de
                                origen</h2>

                            <Form.Row className={"mt30"}>
                                <Form.Group as={Col} xs="3">
                                    <Form.Control
                                        autoComplete={"new-password"}
                                        size={'sm'}
                                        type="number"
                                        name="phone_code"
                                        value={values.phone_code}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        isValid={touched.phone_code && !errors.phone_code}
                                        isInvalid={!!errors.phone_code}
                                    />
                                    <Form.Label className={"text-uppercase"}>C de área</Form.Label>
                                    <small className="form-text text-muted">
                                        Sin 0
                                    </small>
                                    <Form.Control.Feedback type="invalid">
                                        {errors.phone_code}
                                    </Form.Control.Feedback>
                                </Form.Group>
                                <Form.Group as={Col} xs="9">
                                    <Form.Control
                                        autoComplete={"new-password"}
                                        size={'sm'}
                                        type="number"
                                        name="phone_number"
                                        value={values.phone_number}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        isValid={touched.phone_number && !errors.phone_number}
                                        isInvalid={!!errors.phone_number}
                                    />
                                    <Form.Label className={"text-uppercase"}>Número de teléfono</Form.Label>
                                    <small className="form-text text-muted">
                                        Sin 15
                                    </small>

                                    <Form.Control.Feedback type="invalid">
                                        {errors.phone_number}
                                    </Form.Control.Feedback>
                                </Form.Group>
                            </Form.Row>
                            <div className={"text-center"}>
                                <Button type="submit" size="lg" className={"rounded-circle"}>
                                    <i className="fa fa-paper-plane"></i>
                                </Button>
                            </div>
                        </Form>
                    )
                }}
            </Formik>
        </Col>
    </Row>
}

const mapStateToProps = state => {
    return {
        packages: getPackagesSelector(state),
        store: getSelectedStore(state),
        user: userSelector(state),
    }
}
const mapDispatchToProps = dispatch => bindActionCreators({
    dispatchContactCountry: sendContactCountry,
    dispatchPreRegister: _preRegister,
    dispatchSelectPackage: _setPackageCode,
    dispatchRegister: signIn,
    dispatchLoadPackages: getPackages,
}, dispatch)

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(RegisterComplete))

RegisterComplete.propTypes = {
    packages: PropTypes.array,
    provinces: PropTypes.array,
    store: PropTypes.object.isRequired,
}
