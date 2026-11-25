import React from "react"
import PropTypes from 'prop-types'
import {Button, Col, Container, Image, Row} from "react-bootstrap"

import suitcase from '../../../layouts/IndividualTourism/img/suitcase.png'
import service from '../../../layouts/IndividualTourism/img/service.png'
import gift from '../../../layouts/IndividualTourism/img/gift.png'
import calender from '../../../layouts/IndividualTourism/img/calender.png'
import hotel from '../../../layouts/IndividualTourism/img/hotel.png'
import person from '../../../layouts/IndividualTourism/img/person.png'
import assist from '../../../layouts/IndividualTourism/img/assist.png'
import assist_white from '../../../layouts/IndividualTourism/img/asisst-white.png'
import moment from 'moment'
import {isMobile} from "../../../common/utils";
import imgMobile from '../../../layouts/IndividualTourism/img/pack-detail-modile.jpeg'
import imgDesktop from '../../../layouts/IndividualTourism/img/pack-detail.jpeg'
const IndividualProductDetail = ({
                                     dateRanges = [],
                                     date_range_index = 0,
                                     route
                                 }) => {

    let date_start, name
    if (dateRanges[date_range_index]) {
        name = dateRanges[date_range_index].name
        date_start = dateRanges[date_range_index].date_start
    }

    return <Container className={'mt-3'}>
        {isMobile() ? <Image fluid src={imgMobile}/>:
            <Image fluid src={imgDesktop}/>}
        <Row className={'mt-4 mx-3'}>
            <Col xs={12} className={'text-center'}>
                <div className={'d-flex justify-content-center'}>
                    <Image src={calender} width={34} className={'mr-2'}/>
                    <small className={'align-self-center text-left d-block mt-2'}>Salida desde Bs.As. {date_start ? moment(date_start).format('DD/MM/YYYY') : ''}</small>
                </div>
            </Col>
        </Row>
        <Row className={'mt-4 mx-3 justify-content-center'}>
            <Col xs={12} sm={4} className={'text-center'}>
                <div className={'d-flex justify-content-center'}>
                    <div className={'d-flex border border-primary rounded p-3'}>
                        <div>
                            <Image src={gift} width={34} className={'mr-2'}/>
                        </div>
                        <div className={'text-left'}>
                            <small className={'d-block'}>Utilizá tu Bono del 50%</small>
                            <small className={'d-block'}>de regalo, en este Pack</small>
                        </div>
                    </div>
                    <div className={'ml-2 align-self-center'}>
                        <i className={'fa fa-check-circle-o fa-2x text-success'}></i>
                    </div>
                </div>
            </Col>
        </Row>
        <Row className={"justify-content-center my-4 mx-3"}>
            <Col xs={8} sm={2}>
                <Button variant={'success'}
                        block
                        onClick={() => route('register')}
                        className={'text-lowercase text-primary rounded-pill px-4 px-4'}><span>Cotizar</span></Button>
            </Col>
        </Row>
        <Row className={'mt-4 mx-3 d-block d-sm-none'}>
            <Col xs={12}>
                <Image src={assist}/>
                <p className={'my-0'}>Atención personalizada en todos</p>
                <p className={'my-0'}>los destinos por el staff de Armatrip</p>
            </Col>
        </Row>
    </Container>
}

IndividualProductDetail.propTypes = {
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    image: PropTypes.string,
    name: PropTypes.string.isRequired,
    price: PropTypes.number,
    products: PropTypes.array.isRequired,
}

IndividualProductDetail.defaultProps = {
    products: [],
}

export default IndividualProductDetail