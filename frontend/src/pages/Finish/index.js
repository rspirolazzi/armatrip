import React from 'react'
import {Col, Row} from "react-bootstrap"

const Finish = () => <Row className="justify-content-md-center">
    <Col xs={12} md={6}>
        <div className={'d-none d-md-block mt30 mb30'}>
            <h3 className={"text-center black text-brand"}>Gracias</h3>
            <h4 className={"text-center black text-brand"}>por cotizar tu viaje</h4>
            <h4 className={"text-center black text-brand"}>con nosotros!</h4>
        </div>
        <div className={'d-block d-sm-none'}>
            <div className="text-center static-screen img3 mx-4">
                <div>
                    <h1 className={"bold font-size-64 mt100"}>Gracias</h1>
                    <div className={"bold font-size-24 mt30"}>Tu viaje esta cada<br></br>vez màs cerca!</div>
                </div>
            </div>
        </div>
    </Col>
</Row>
export default Finish
