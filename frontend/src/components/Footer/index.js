import React from 'react'
import {Col, Row} from "react-bootstrap"

const Footer = () => <>
    <div className="d-block d-sm-none">
        <div className="progress">
            <div className="progress-bar bg-info" role="progressbar" style={{width: '20%'}}></div>
            <div className="progress-bar bg-accent" role="progressbar" style={{width: '40%'}}></div>
            <div className="progress-bar bg-warning" role="progressbar" style={{width: '10%'}}></div>
            <div className="progress-bar bg-info" role="progressbar" style={{width: '30%'}}></div>
        </div>
    </div>
    <footer className={'d-none d-sm-block'}>
        <Row className="justify-content-md-center">
            <Col xs className={'bg-accent'}>
                <p className={'mb-0 ml-4'}>Calculá el</p>
                <p className={'mb-0 ml-4'}>precio exacto</p>
                <p className={'mb-0 ml-4'}>de tu viaje</p>
            </Col>
            <Col xs className={'bg-info text-white'}>
                <p className={'mb-0 ml-4'}>Armalo a</p>
                <p className={'mb-0 ml-4'}>tu gusto y</p>
                <p className={'mb-0 ml-4'}>presupuesto</p>
            </Col>
            <Col xs className={'bg-highlight'}>
                <p className={'mb-0 ml-4'}>Elegí la</p>
                <p className={'mb-0 ml-4'}>opción que</p>
                <p className={'mb-0 ml-4'}>mas te convenga</p>
            </Col>
        </Row>
    </footer>
</>
export default Footer