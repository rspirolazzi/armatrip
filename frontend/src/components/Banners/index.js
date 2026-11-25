import React from 'react'
import {Carousel, Col, Container, Image, Row} from "react-bootstrap";
import gift_white from '../../layouts/IndividualTourism/img/gift-white.png'
import assist_white from '../../layouts/IndividualTourism/img/asisst-white.png'
import map_white from '../../layouts/IndividualTourism/img/map-white.png'

import gift_blue from '../../layouts/IndividualTourism/img/gift.png'
import assist_blue from '../../layouts/IndividualTourism/img/assist.png'
import map_blue from '../../layouts/IndividualTourism/img/map.png'

const values = [
    {img_white: gift_white, img_blue: gift_blue, text: ['Contratando un pack obtené tu', 'Bono del 50% de regalo!']},
    {
        img_white: assist_white,
        img_blue: assist_blue,
        text: ['Atención personalizada en todos', 'los destinos por el staff de Armatrip']
    },
    {img_white: map_white, img_blue: map_blue, text: ['Sumá actividades en los', 'destinos!']},
]
const Banners = () => <>
    <Container className={'d-none d-sm-block justify-content-center my-4'}>
        <Row>
            {values.map((value, i) => <Col key={i} sm={4}>
                <div className={'text-primary d-flex flex-row justify-content-between'}>
                    <Image className={'mr-2'} height={'60'} src={value.img_blue}/>
                    <div className={'text-left align-self-center'}>
                        {value.text.map((text, i) => <p key={i} className={'m-0'}>{text}</p>)}
                    </div>
                </div>
            </Col>)}
        </Row>
    </Container>
    <Carousel controls={false} indicators={false} className={'d-block d-sm-none'}>
        {values.map((value, i) => <Carousel.Item key={i}>
            <Row className={'bg-primary text-white justify-content-center px-3 my-2 py-1'}>
                <Col xs={2} className={'text-right pr-1'}>
                    <Image fluid src={value.img_white}/>
                </Col>
                <Col xs={10} className={'text-left pl-1 font-size-sm'}>
                    {value.text.map((text, i) => <p key={i} className={'m-0'}>{text}</p>)}
                </Col>
            </Row>
        </Carousel.Item>)}
    </Carousel>
</>
export default Banners