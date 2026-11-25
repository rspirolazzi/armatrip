import React from 'react'
import logo from '../Header/img/logo.png'
import {Col, Container, Image, Row} from "react-bootstrap";
import {compose} from "redux";
import {connect} from "react-redux";
import Newsletter from "../../../components/Newsletter";
import BannerCreditCard from "../../../components/Individual/BannerCreditCard";
import {AGENCY_LICENSE, CONTACT_ADDRESS, CONTACT_EMAIL, CONTACT_PHONE} from "../../../common/constants";
import assist_white from "../img/asisst-white.png";

const Footer = ({
                    is_classic = false,

                }) => <>
    <div id={'assist'} className={'bg-primary justify-content-center py-2 mt-4'}>
        <Image height={50} src={assist_white} className={'mr-2'}/>
        <div className={'align-self-center text-white'}>
            <p className={'my-0 book'}>Atención personalizada en todos</p>
            <p className={'my-0 book'}>los destinos por el staff de Armatrip</p>
        </div>
    </div>
    <BannerCreditCard/>
    <div className={'d-none d-sm-block bg-secondary mt-4'}>
        <Container as={'footer'}>
            <Row>
                <Col xs={4}>
                    <Image width={150} src={logo} className={'mt-n4'}/>
                    <div className={'text-primary text-uppercase'}>
                        {CONTACT_PHONE && <h2>{CONTACT_PHONE}</h2>}
                        {CONTACT_ADDRESS && <p className={'book'}>{CONTACT_ADDRESS}</p>}
                        {CONTACT_EMAIL && <p className={'book'}>{CONTACT_EMAIL}</p>}
                        {AGENCY_LICENSE && <p className={'book'}>Nº de legajo: {AGENCY_LICENSE}</p>}
                    </div>
                </Col>
                <Col xs={8} className={'pt-4'}>
                    <Newsletter/>
                </Col>
            </Row>
        </Container>
    </div>
</>
const mapStateToProps = state => ({})

export default compose(
    connect(mapStateToProps, null)
)(Footer)
