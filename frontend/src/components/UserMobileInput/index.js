import React from "react"
import {Formik} from 'formik'
import {Button, Col, Form} from "react-bootstrap"
import schema from './_.schema'
import {withRouter} from "react-router"
import {connect} from 'react-redux'
import {bindActionCreators} from 'redux'
import {userSelector} from "../../store/user/selectors"
import {signIn} from "../../store/user/operators"
import {_preRegister} from "../../store/user"

const UserMobileInput = props => <Formik
    validationSchema={schema}
    onSubmit={(data) => {
        let userComplete = {...props.user, ...data, verification_type: 'sms'}
        props.dispatchPreRegister(userComplete)
        props.dispatchRegister(userComplete)
            .then(resp => {
                //props.history.push('/packages')
                return resp
            })
    }}
    initialValues={{
        phone_code: '',
        phone_number: '',
    }}>
    {({
          handleSubmit,
          handleChange,
          handleBlur,
          values,
          touched,
          isValid,
          errors,
      }) => (
        <Form noValidate onSubmit={handleSubmit}>
            <Form.Row>
                <Form.Group as={Col} xs="5">
                    <Form.Control
                        type="number"
                        name="phone_code"
                        value={values.phone_code}
                        onChange={handleChange}
                        onBlur={handleBlur}
                        isValid={touched.phone_code && !errors.phone_code}
                        isInvalid={!!errors.phone_code}
                    />
                    <Form.Label className={"text-uppercase"}>Código de área</Form.Label>
                    <small className="form-text text-muted">
                        Sin 0
                    </small>
                    <Form.Control.Feedback type="invalid">
                        {errors.phone_code}
                    </Form.Control.Feedback>
                </Form.Group>
                <Form.Group as={Col} xs="7">
                    <Form.Control
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
    )}
</Formik>
const mapStateToProps = (state, props) => {
    return {
        user: userSelector(state)
    }
}
const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    dispatchPreRegister: _preRegister,
    dispatchRegister: signIn,
}, dispatch)

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(UserMobileInput))