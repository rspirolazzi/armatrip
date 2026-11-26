import React, {useEffect, useState} from 'react'
import {connect} from "react-redux"
import SelectInput from "../../components/SelectInput"
import {bindActionCreators} from "redux"
import {_setOrderDate} from "../../store/order"
import {Col, Row} from "react-bootstrap"
import SkyButton from "../../components/SkyButton"
import {getSelectedStore} from "../../store/store/selectors"
import {route} from "../../common/utils"


const SelectDate = ({dispatchSetOrderDate, history, store:{code}}) => {
    const [month, setMonth] = useState(0)
    const [year, setYear] = useState(0)

    const _years = []

    useEffect(() => {

    });

    const getYears = () => {
        if (_years.length === 0) {
            const initYear = (new Date()).getFullYear()
            for (let i = initYear; i <= 5 + initYear; i++) {
                _years.push(i)
            }
        }
        return _years
    }

    const getMonths = () => {
        return ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',]
    }

    const handleMonth = (index_month) => {
        setMonth(index_month)
    }
    const handleYear = (index_year) => {
        setYear(index_year)
    }
    const setDate = (biweekly) => {
        dispatchSetOrderDate({
            biweekly,
            month: (month+1),
            year: _years[year]
        })
        history.push(route('packages', [code]))
    }

    return <div>
        <h4 cdispatchSetOrderDatedispatchSetOrderDatelassName={'text-brand text-center font-weight-bold'}>Ingresá tu fecha de viaje</h4>
        <Row className={'my-4'}>
            <Col xs={12} className={"text-center"}>
                <SelectInput handleChange={handleYear} options={getYears()}/>
            </Col>
        </Row>
        <Row className={'mb-4'}>
            <Col xs={12} className={"text-center"}>
                <SelectInput handleChange={handleMonth} options={getMonths()}/>
            </Col>
        </Row>
        <Row>
            <Col xs={6} className={"text-right"}>
                <SkyButton onClick={() => setDate(1)}>
                    <h1 className={'my-0 mx-0'}>1°</h1>
                    <small>Quincena</small>
                </SkyButton>
            </Col>
            <Col xs={6} className={"text-left"}>
                <SkyButton onClick={() => setDate(2)}>
                    <h1 className={'my-0 mx-0'}>2°</h1>
                    <small>Quincena</small>
                </SkyButton>
            </Col>
        </Row>
    </div>
}
const mapStateToProps = (state, props) => {
    return {
        store:getSelectedStore(state)
    }
}
const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    dispatchSetOrderDate: _setOrderDate,
}, dispatch)
export default connect(mapStateToProps, mapDispatchToProps)(SelectDate)