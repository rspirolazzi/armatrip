import React, {useState} from 'react'
import {Formik} from 'formik'
import {Col, Form, Row} from "react-bootstrap"
import schema from './_.schema'
import {withRouter} from "react-router"
import {connect} from 'react-redux'
import {bindActionCreators} from 'redux'
import {_preRegister, _sendPhone, _setUserIsValidClear} from "../../store/user"
import {getPackageSelector} from "../../store/package/selectors"
import UserMobileActivation from "../../components/UserMobileActivation"
import {signIn, signInWithToken} from "../../store/user/operators"
import {isPhoneSentSelector, isValidStateSelector, jwtStateSelector, userSelector} from "../../store/user/selectors"
import SkyButton from "../../components/SkyButton"
import {route} from "../../common/utils";
import {delJWT} from "../../services/Base";

const RegisterComplete = ({
                              dispatchLoadPackages,
                              store,
                              _preRegister,
                              _sendPhone,
                              _register,
                              _registerWithToken,
                              history,
                              user,
                              package_detail,
                              phoneSent,
                              is_valid,
                              packageDetail,
                              _setUserIsValidClear,
                              jwt
                          }) => {

    const [sendingRegister, setSendingRegister] = useState(false)

    const submitWithToken = () => {
        let userComplete = {...user, package_code: package_detail.code}
        _preRegister(userComplete)
        _registerWithToken(userComplete)
            .then(resp => {
                history.push(route('packageDetail', [packageDetail.code]))
                return resp
            }).catch(err => {
            setSendingRegister(false)
            _sendPhone(true)//TODO VER SI NO HAY QUE SACARLO
            return err
        });
        setSendingRegister(true)
    }
    const submit = (data = {}) => {
        let userComplete = {...user, ...data, package_code: package_detail.code}
        _preRegister(userComplete)
        _register(userComplete)
            .then(resp => {
                setTimeout(() => {
                    setSendingRegister(false)
                    _sendPhone(true)
                }, 3000)
                return resp
            }).catch(err => {
            setSendingRegister(false)
            _sendPhone(true)//TODO VER SI NO HAY QUE SACARLO
            return err
        });
        setSendingRegister(true)
    }

    return is_valid ? <>
            <Row className="justify-content-center my-4">
                <Col xs={12} md={6}>
                    <h5 className={"black text-uppercase text-center text-info font-weight-300"}>{user.name} estas
                        registrado <br/> con el celular </h5>
                    <h5 className={"black text-uppercase text-center text-info font-weight-300"}>{user.phone_code} - {user.phone_number}</h5>
                </Col>
            </Row>
            <Row className="justify-content-center my-4">
                <Col xs={12} md={6}>
                    <div className={"text-center mb-3"}>
                        <a onClick={() => submitWithToken()} className={'btn btn-brand rounded-pill'}>Continuar
                            con este número</a>
                    </div>
                </Col>
                <Col xs={12} md={6}>
                    <div className={"text-center mb-3"}>
                        <a onClick={() => {
                            delJWT()
                            _setUserIsValidClear(false)
                            _sendPhone(false)
                        }} className={'btn'}>Cambiar de número</a>
                        <p>
                            <small className={'text-muted'}>Se te solicitará un nuevo número de celular para validar tu
                                cotización</small>
                        </p>
                    </div>
                </Col>
            </Row>
        </> :
        <>
            <Formik
                validationSchema={schema}
                onSubmit={(data) => {
                    submit(data)
                }}
                initialValues={{
                    phone_code: user.phone_code || '',
                    phone_number: user.phone_number || '',
                }}>
                {({
                      handleSubmit,
                      handleChange,
                      handleBlur,
                      values,
                      touched,
                      isValid,
                      errors,
                  }) => {
                    return (
                        !phoneSent && <Form noValidate onSubmit={handleSubmit} className={'my-4'}>
                            <Row className="justify-content-center">
                                <Col xs={12} md={6}>
                                    <h4 className={"black text-uppercase text-center text-info font-weight-300"}>Envianos
                                        un
                                        teléfono para<br/> válidar la cotización</h4>
                                </Col>
                            </Row>
                            <Row className="justify-content-center my-4">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={4}>
                                            <Form.Control
                                                autoComplete={"new-password"}
                                                size={'sm'}
                                                type="number"
                                                name="phone_code"
                                                min="0"
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
                                        <Form.Group as={Col} xs={8}>
                                            <Form.Control
                                                autoComplete={"new-password"}
                                                size={'sm'}
                                                type="number"
                                                min="0"
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
                                </Col>
                            </Row>
                            <Row className="justify-content-center my-4">
                                <Col xs={12} md={6}>
                                    <div className={"text-center"}>
                                        <SkyButton type="submit">
                                            {sendingRegister && <i className={"fa fa-spinner fa-spin"}></i>}
                                            {!sendingRegister && <i className={"fa fa-paper-plane-o"}></i>}
                                        </SkyButton>
                                    </div>
                                </Col>
                            </Row>
                        </Form>
                    )
                }}
            </Formik>
            {phoneSent && <Row className="justify-content-center">
                <Col xs={12} md={6}>
                    <div className={'mt-4 mx-4'}>
                        <div className="font-size-20 line1">
                            <p className="black text-info font-weight-bold text-center mb-5">Te enviaremos un código por
                                SMS al {user.phone_code} {user.phone_number} </p>
                        </div>
                        <UserMobileActivation
                            phone_code={user.phone_code}
                            phone_number={user.phone_number}
                        />
                        <p className={"text-center mt30 font-size-14 text-brand"}><a style={{cursor: 'pointer'}}
                                                                                   className={'font-weight-lighter'}
                                                                                   onClick={() => _sendPhone(false)}>Volver
                            a ingresar el número de teléfono</a></p>
                    </div>
                </Col></Row>
            }
        </>
}

const mapStateToProps = state => {
    return {
        user: userSelector(state),
        is_valid: isValidStateSelector(state),
        package_detail: getPackageSelector(state),
        phoneSent: isPhoneSentSelector(state),
        packageDetail: getPackageSelector(state),
        jwt: jwtStateSelector(state),
    }
}
const mapDispatchToProps = dispatch => bindActionCreators({
    _preRegister: _preRegister,
    _sendPhone: _sendPhone,
    _register: signIn,
    _registerWithToken: signInWithToken,
    _setUserIsValidClear: _setUserIsValidClear,
}, dispatch)

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(RegisterComplete))

RegisterComplete.propTypes = {}
RegisterComplete.defaultProps = {}
