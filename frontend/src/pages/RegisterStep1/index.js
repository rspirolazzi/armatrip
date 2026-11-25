import PropTypes from 'prop-types'
import React, {useEffect, useLayoutEffect, useState} from 'react'
import {Formik} from 'formik'
import {Col, Form, Row} from "react-bootstrap"
import schema from './_.schema'
import {withRouter} from "react-router"
import {connect} from 'react-redux'
import {bindActionCreators} from 'redux'
import {_preRegister, _sendPhone, _setUserIsValidClear} from "../../store/user"
import AutoComplete from "../../components/AutoComplete"
import {getProvinceSelectedSelector, getProvincesSelector} from "../../store/province/selectors"
import {getPackageSelector} from "../../store/package/selectors"
import {getSelectedStore} from "../../store/store/selectors"
import SelectYear from "../../components/SelectYear"
import {jwtStateSelector, userSelector} from "../../store/user/selectors"
import PurpleButton from "../../components/PurpleButton"
import {Link} from "react-router-dom"
import {route} from "../../common/utils"
import AutoCompleteAsync from "../../components/AutoCompleteAsync";
import {getSchools} from "../../store/school/operations";
import {getSchoolsSearchingSelector, getSchoolsSelector} from "../../store/school/selectors";
import {STORE_CODE_TURISMO_INDIVIDUAL} from "../../common/constants";
import {getUser} from "../../store/user/operators";
import {delJWT} from "../../services/Base";

const RegisterComplete = ({
                              store,
                              _preRegister,
                              history,
                              user,
                              provinces,
                              package_detail,
                              _sendPhone,
                              _getSchools,
                              schools,
                              schools_searching,
                              jwt,
                              _getUser,
                              _setUserIsValidClear
                          }) => {

    const [dates, setDates] = useState(null)

    const setDateFromPackage = (package_detail) => {
        let _dates = null
        if (package_detail && package_detail.dateRanges && package_detail.dateRanges.length > 0) {
            _dates = package_detail.dateRanges.map(date => ({
                year: date.year,
                month: date.month,
                biweekly: date.biweekly
            }))
        }
        setDates(_dates)
    }

    useEffect(() => {
        if (jwt) {
            _getUser(jwt)
                .then(res => console.log(res))
                .catch(err => {
                    console.log('Error', err)
                    _setUserIsValidClear()
                })
        }else{
            delJWT()
        }
    }, [jwt])

    useEffect(() => {
        setDateFromPackage(package_detail);
        _sendPhone(false);
    }, [package_detail && package_detail.code])

    useLayoutEffect(() => {
        window.scrollTo(0, 0)
    })
    return <Formik
        validationSchema={schema}
        onSubmit={(data) => {
            let userComplete = {...data, verification_type: 'sms', store_code: store.code}
            _preRegister(userComplete)
            history.push('/mobile')
        }}
        initialValues={{
            name: user.name || '',
            email: user.email || '',
            package_code: user.package_code || null,
            year: 0,
            month: 0,
            biweekly: 0,
            qty: '',
            province_code: user.province_code || '',
            province_name: user.province_name || '',
            school_showed: (store.code !== STORE_CODE_TURISMO_INDIVIDUAL),
            school_name: user.school_name || '',
            school_id: user.school_id || null,
            school_city: user.school_city || '',
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
            const getYears = () => {
                let deep = 5, from = (new Date()).getFullYear(), options = []
                if (dates && dates.length > 0) {
                    dates.forEach(d => {
                        if (!options.find(o => o.value === d.year)) {
                            options.push({value: d.year, name: d.year})
                        }
                    })
                } else {
                    for (let i = 0; i <= deep; i++) {
                        options.push({value: from + i, name: from + i})
                    }
                }
                return options
            }
            const getMonths = () => {
                let result = [
                    {value: 1, name: 'Enero',},
                    {value: 2, name: 'Febrero',},
                    {value: 3, name: 'Marzo',},
                    {value: 4, name: 'Abril',},
                    {value: 5, name: 'Mayo',},
                    {value: 6, name: 'Junio',},
                    {value: 7, name: 'Julio',},
                    {value: 8, name: 'Agosto',},
                    {value: 9, name: 'Septiembre',},
                    {value: 10, name: 'Octubre',},
                    {value: 11, name: 'Noviembre',},
                    {value: 12, name: 'Diciembre',},
                ]
                const selected_year = parseInt(values.year)
                if (selected_year && selected_year !== 0 && dates && dates.length > 0) {
                    let result_to_filter = dates.filter(d => d.year === selected_year)
                    if (!result_to_filter.every(d => d.month == null)) {
                        result = result.filter(m => result_to_filter.find(f => f.month === m.value))
                    }
                }
                return result
            }
            const getBiweeklies = () => {
                let biweeklies = [
                    1, 2
                ]
                const selected_year = parseInt(values.year)
                const selected_month = parseInt(values.month)
                if (selected_year && selected_year !== 0 && selected_month && selected_month !== 0 && dates && dates.length > 0) {
                    let biweeklies_to_filter = dates.filter(d => ((d.year === selected_year) && (d.month === selected_month)))
                    if (!biweeklies_to_filter.every(x => x.biweekly == null)) {
                        biweeklies = biweeklies.filter(m => biweeklies_to_filter.find(f => f.biweekly === m))
                    }
                }
                return biweeklies
            }

            return (
                <Form noValidate onSubmit={handleSubmit}>
                    <div className={'bg-info pt-4'}>
                        <Row className="justify-content-md-center">
                            <Col xs={12} md={6}>
                                <Form.Row className={'mx-4'}>
                                    <Form.Group as={Col} xs={12} className={'mb-0'}>
                                        <AutoComplete
                                            size={'sm'}
                                            autoComplete={"new-password"}
                                            type="text"
                                            name="province_code"
                                            value={values.province_code}
                                            placeholder="TU PROVINCIA O PAIS"
                                            onSelectSuggest={(suggestion) => {
                                                setFieldValue('province_code', suggestion.code, true)
                                                setFieldValue('province_name', suggestion.name, true)
                                            }}
                                            isValidSuggest={(value) => {
                                                if (!value) {
                                                    setFieldValue('province_code', '', true)
                                                    setFieldValue('province_name', '', true)
                                                }
                                            }}
                                            suggestions={provinces}
                                            isValid={touched.province_code && !errors.province_code}
                                            isInvalid={!!errors.province_code}
                                        />
                                        <Form.Label className={"text-uppercase"}>De donde son?</Form.Label>
                                        <Form.Control.Feedback type="invalid">
                                            {errors.province_code}
                                        </Form.Control.Feedback>
                                    </Form.Group>
                                </Form.Row>
                            </Col>
                        </Row>
                        {(store.code !== 'turismo_individual') && <>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <AutoCompleteAsync
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="school_name"
                                                value={values.school_name}
                                                placeholder="TU COLEGIO"
                                                searching={schools_searching}
                                                suggestions={schools}
                                                optionId={'id'}
                                                optionDisplay={'name'}
                                                optionSubDisplay={(value) => `${value.city} - ${value.province}`}
                                                handleSearch={(term) => {
                                                    _getSchools(term, values.province_name)
                                                }}
                                                handleOnChange={text => {
                                                    setFieldValue('school_name', text, true)
                                                    setFieldValue('school_id', null, true)
                                                }}
                                                handleOnBlur={value => {
                                                    if (value) {
                                                        if (typeof value === 'object') {
                                                            setFieldValue('school_name', value.name, true)
                                                            setFieldValue('school_id', value.id, true)
                                                            setFieldValue('school_city', value.city, true)
                                                        }
                                                    }
                                                }}
                                                handleOnSelect={value => {
                                                    if (value) {
                                                        if (typeof value === 'object') {
                                                            setFieldValue('school_name', value.name, true)
                                                            setFieldValue('school_id', value.id, true)
                                                            setFieldValue('school_city', value.city, true)
                                                        } else {
                                                            setFieldValue('school_name', value, true)
                                                            setFieldValue('school_id', null, true)
                                                            setFieldValue('school_city', null, true)
                                                        }
                                                    }
                                                }}
                                                isValid={touched.school_name && !errors.school_name}
                                                isInvalid={!!errors.school_name}
                                            />
                                            <Form.Label className={"text-uppercase"}>Colegio</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors._school_name}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="school_city"
                                                name="school_city"
                                                value={values.school_city}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.school_city && !errors.school_city}
                                                isInvalid={!!errors.school_city}
                                            />
                                            <Form.Label className={"text-uppercase"}>Localidad</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.school_city}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                        </>}
                        <Row className="justify-content-md-center">
                            <Col xs={12} md={6}>
                                <Form.Row className={'mx-4'}>
                                    <Form.Group as={Col} xs={12} className={'mb-0'}>
                                        <Form.Control
                                            size={'sm'}
                                            autoComplete={"new-password"}
                                            type="number"
                                            name="qty"
                                            min="1"
                                            value={values.qty}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                            isInvalid={!!errors.qty}
                                        />
                                        <Form.Label className={"text-uppercase"}>CANT. DE PASAJEROS</Form.Label>
                                        <Form.Control.Feedback type="invalid">
                                            {errors.qty}
                                        </Form.Control.Feedback>
                                    </Form.Group>
                                </Form.Row>
                            </Col>
                        </Row>
                        {false ? <>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <p className={'mx-4 mb-0'}>
                                        Tu nombre: {values.name}
                                    </p>
                                </Col>
                            </Row>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <p className={'mx-4 mb-0'}>Tu email: {values.email}</p>
                                </Col>
                            </Row>
                        </> : <>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="text"
                                                name="name"
                                                value={values.name}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.name && !errors.name}
                                                isInvalid={!!errors.name}
                                            />
                                            <Form.Label className={"text-uppercase"}>Tu Nombre</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.name}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                            <Row className="justify-content-md-center">
                                <Col xs={12} md={6}>
                                    <Form.Row className={'mx-4'}>
                                        <Form.Group as={Col} xs={12} className={'mb-0'}>
                                            <Form.Control
                                                size={'sm'}
                                                autoComplete={"new-password"}
                                                type="email"
                                                name="email"
                                                value={values.email}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                isValid={touched.email && !errors.email}
                                                isInvalid={!!errors.email}
                                            />
                                            <Form.Label className={"text-uppercase"}>Tu Email</Form.Label>
                                            <Form.Control.Feedback type="invalid">
                                                {errors.email}
                                            </Form.Control.Feedback>
                                        </Form.Group>
                                    </Form.Row>
                                </Col>
                            </Row>
                        </>}
                    </div>
                    <Row className="justify-content-md-center">
                        <Col xs={12} md={6}>
                            <p className={"black font-size-18 text-primary font-weight-bold text-center text-uppercase my-2"}>¿Cuando
                                viajan?</p>
                        </Col>
                    </Row>
                    <Row className="justify-content-md-center">
                        <Col xs={12} md={6}>
                            <Form.Row className={'mx-4 mb-4'}>
                                <Form.Group as={Col} xs={4} className={"mb-0"}>
                                    <SelectYear
                                        size={'sm'}
                                        autoComplete={"new-password"}
                                        placeholder="AÑO"
                                        name="year"
                                        value={values.year}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        isValid={touched.year && !errors.year}
                                        isInvalid={!!errors.year}
                                        from={getYears()}
                                        className={'bg-primary'}
                                    />
                                    <Form.Control.Feedback type="invalid">
                                        {errors.year}
                                    </Form.Control.Feedback>
                                </Form.Group>
                                <Form.Group as={Col} xs={5} className={"mb-0"}>
                                    <SelectYear
                                        size={'sm'}
                                        autoComplete={"new-password"}
                                        placeholder="MES"
                                        name="month"
                                        value={values.month}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        isValid={touched.month && !errors.month}
                                        isInvalid={!!errors.month}
                                        from={getMonths()}
                                        className={'bg-primary'}
                                    />
                                    <Form.Control.Feedback type="invalid">
                                        {errors.month}
                                    </Form.Control.Feedback>
                                </Form.Group>
                                <Form.Group as={Col} xs={3} className={"mb-0"}>
                                    <Form.Control
                                        as={"select"}
                                        size={'sm'}
                                        autoComplete={"new-password"}
                                        placeholder="QUINCENA"
                                        type="number"
                                        min="0"
                                        name="biweekly"
                                        value={values.biweekly}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        isValid={touched.biweekly && !errors.biweekly}
                                        isInvalid={!!errors.biweekly}
                                        className={'bg-primary'}
                                    >
                                        <option value={0}>Quincena</option>
                                        {getBiweeklies().map(b => <option key={b} value={b}>{b}</option>)}
                                    </Form.Control>
                                    <Form.Control.Feedback type="invalid">
                                        {errors.biweekly}
                                    </Form.Control.Feedback>
                                </Form.Group>
                            </Form.Row>
                        </Col>
                    </Row>
                    <Row className="justify-content-center">
                        <Col xs={12} md={6}>
                            <div className={"text-center mb-3"}>
                                <PurpleButton type={'submit'}>OK</PurpleButton>
                            </div>
                            <div className={"text-center mb-3"}>
                                <Link to={route('selectPackages', [store.code])}
                                      className={'btn btn-brand rounded-pill'}>Volver</Link>
                            </div>
                        </Col>
                    </Row>
                </Form>
            )
        }}
    </Formik>
}

const mapStateToProps = state => {
    return {
        provinces: getProvincesSelector(state),
        province: getProvinceSelectedSelector(state),
        store: getSelectedStore(state),
        user: userSelector(state),
        package_detail: getPackageSelector(state),
        schools: getSchoolsSelector(state),
        schools_searching: getSchoolsSearchingSelector(state),
        jwt: jwtStateSelector(state),
    }
}
const mapDispatchToProps = dispatch => bindActionCreators({
    _preRegister: _preRegister,
    _sendPhone: _sendPhone,
    _getSchools: getSchools,
    _getUser: getUser,
    _setUserIsValidClear: _setUserIsValidClear,
}, dispatch)

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(RegisterComplete))

RegisterComplete.propTypes = {
    provinces: PropTypes.array,
    store: PropTypes.object.isRequired,

}
RegisterComplete.defaultProps = {
    provinces: [],
}
