import React from 'react'
import {url_src} from "../../../store/core/_.utils";
import {Container, Image} from "react-bootstrap";

const BannerCreditCard = () => <Container id={'credit_card_list'}>
    <div className={'d-none m-4 d-sm-flex flex-column justify-content-center text-center'}>
        <h3 className={'book'}>PAGA TUS VIAJES EN CUOTAS</h3>
        <div>
            <Image className={'w-50'} src={url_src('img/credit_card_icons/vetical.jpg')}/>
        </div>
    </div>
</Container>

export default BannerCreditCard