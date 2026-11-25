import React, {useEffect, useState} from "react"
import {Formik} from 'formik'
import {Col, Form} from "react-bootstrap"
import schema from './_.schema'
import {withRouter} from "react-router"
import {connect} from 'react-redux'
import {bindActionCreators} from 'redux'
import {userSelector} from "../../store/user/selectors"
import {verificationSMS} from "../../store/user/operators"
import {getSelectedStore} from "../../store/store/selectors"
import PurpleButton from "../PurpleButton"
import {getPackageSelector} from "../../store/package/selectors"
import {route} from "../../common/utils"

var timerID = null
const UserMobileActivation = ({
                                  _verificationSMS,
                                  history,
                                  packageDetail,
                                  phone_code,
                                  phone_number,
                              }) => {
    const [seconds, setSeconds] = useState(45);

    //Replaces componentDidMount and componentWillUnmount
    useEffect(() => {
        if (seconds > 0) {
            timerID = setInterval(() => tick(seconds - 1), 1000);
        } else {
            clearInterval(timerID);
        }

        return function cleanup() {
            clearInterval(timerID);
        };
    });

    function tick(value) {
        setSeconds(value);
        if (value <= 0) {
            clearInterval(timerID);
        }
    }

    return <Formik
        validationSchema={schema}
        onSubmit={(data) => {
            _verificationSMS(data.code, phone_code, phone_number).then(() => {
                history.push(route('packageDetail', [packageDetail.code]))
            })
        }}
        initialValues={{
            code: '',
        }}>
        {({
              handleSubmit,
              handleChange,
              handleBlur,
              values,
              touched,
              isValid,
              errors,
              submitForm,
          }) => {
            const handleOnChange = event => {
                handleChange(event)
                if (event.target.value.length === 5) {
                    setTimeout(submitForm, 0);
                }
            }
            return (
                <Form noValidate onSubmit={handleSubmit}>
                    <Form.Row>
                        <Form.Group as={Col} xs={12}>
                            <Form.Control
                                size={'sm'}
                                type="number"
                                autoComplete={"new-password"}
                                placeholder="INGRESÁ EL CÓDIGO"
                                name="code"
                                min="0"
                                value={values.code}
                                onChange={handleOnChange}
                                isValid={touched.code && !errors.code}
                                isInvalid={!!errors.code}
                            />
                            <Form.Control.Feedback type="invalid">
                                {errors.code}
                            </Form.Control.Feedback>
                        </Form.Group>
                    </Form.Row>
                    <div className={"text-center"}>
                        <PurpleButton type="submit">OK</PurpleButton>
                        <div>
                            {seconds ? <small className="text-center text-muted">Recibiendo mensaje en <span
                                style={{
                                    width: '20px',
                                    display: 'inline-block'
                                }}>{seconds}</span> segundos</small> : null}
                        </div>
                    </div>
                </Form>
            )
        }}
    </Formik>
}
const mapStateToProps = (state, props) => {
    return {
        user: userSelector(state),
        packageDetail: getPackageSelector(state),
        store: getSelectedStore(state),
    }
}
const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _verificationSMS: verificationSMS,
}, dispatch)

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(UserMobileActivation))