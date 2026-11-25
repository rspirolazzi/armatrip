import Social from "../Social";
import {Container, Image} from "react-bootstrap";
import logo from "../img/logo.png";
import HeaderTitle from "../HeaderTitle";
import {getIsHeaderClassicStateSelector} from "../../../../store/core/selectors";
import {compose} from "redux";
import {connect} from "react-redux";
import React from "react";
import turismoBackground from '../../img/turismo-background.png'
import packListBackground from '../../img/pack-list-background.png'
import packBackground from '../../img/pack-background.png'
import cotizaBackground from '../../img/cotiza-background.png'
import serviciosBackground from '../../img/servicios-background.png'
import genericoBackground from '../../img/generico-background-01.png'

const HeaderDesktop = ({
                           is_classic = false,
                           match,
                           history,
                           location,
                       }) => {
    let image = turismoBackground
    let title = ''
    if (location.pathname.toString().startsWith('/turismo-individual/packs/')) {
        image = packListBackground
    } else if (location.pathname.toString().startsWith('/turismo-individual/register')
        || (location.pathname.toString().startsWith('/turismo-individual/paquete/') && (location.pathname.toString().endsWith('/servicios')
            || location.pathname.toString().endsWith('/servicios/')
            || location.pathname.toString().endsWith('/confirmacion/') || location.pathname.toString().endsWith('/confirmacion')))) {
        image = genericoBackground
        title = 'Cotiza tu viaje'
    } else if ((location.pathname.toString().startsWith('/turismo-individual/paquete/') && (location.pathname.toString().indexOf('/seleccion'
        !== -1)))) {
        image = genericoBackground
        title = 'Agregar + Servicios'
    } else if (location.pathname.toString().startsWith('/turismo-individual/paquete/')) {
        image = packBackground
    } else if (location.pathname.toString().indexOf('turismo-individual/reservar') !== -1
        || location.pathname.toString().indexOf('turismo-individual/pago') !== -1
        || location.pathname.toString().indexOf('turismo-individual/checkout') !== -1
    ) {
        image = genericoBackground
        if (location.pathname.toString().indexOf('turismo-individual/reservar') !== -1) {
            title = 'Felicitaciones'
        } else if (location.pathname.toString().indexOf('turismo-individual/pago') !== -1) {
            title = 'Reserva'
        } else if (location.pathname.toString().indexOf('turismo-individual/checkout') !== -1) {
            title = 'Listo!'
        }
    }
    return <header className={'desktop'}>
        <div className={'d-sm-flex m-4'}>
            <div className={'mr-auto align-self-center'}>
                <Social/>
            </div>
            <div className={'flex-grow-1 text-center align-self-center'}>
                <ul className={'list-inline text-primary m-0 book text-uppercase'}>
                    <li className={'list-inline-item'}><a href={'#'} target={'_blank'}>Home</a></li>
                    <li className={'list-inline-item'}>|</li>
                    <li className={'list-inline-item'}><a href={'#'} target={'_blank'}>Paquetes</a></li>
                    <li className={'list-inline-item'}>|</li>
                    <li className={'list-inline-item'}><a href={'#'} target={'_blank'}>Ofertas</a></li>
                    <li className={'list-inline-item'}>|</li>
                    <li className={'list-inline-item'}><a href={'#'} target={'_blank'}>Turismo</a></li>
                </ul>
            </div>
        </div>
        <div className={'header position-relative'}>
            <div className={'position-absolute w-100'} style={{zIndex: 9999}}>
                <div className={'d-flex'}>
                    <div className={'ml-auto'}>
                        <Image className={'logo'} width={240} fluid src={logo}/>
                    </div>
                </div>
            </div>
            <div className={'pt-5 carousel-title-container'}>
                <Container>
                    <div className={'position-relative'}>
                        <Image fluid src={image}/>
                        {title ? <div className={'position-absolute w-100'} style={{top: '40%'}}>
                            <h2 className={'text-white text-uppercase book font-size-biggest'}>{title}</h2>
                        </div> : null}
                        <div className={'position-absolute'} style={{bottom: 0}}>
                            <HeaderTitle/>
                        </div>
                    </div>
                </Container>
            </div>
        </div>
    </header>
}

const mapStateToProps = state => ({
    is_classic: getIsHeaderClassicStateSelector(state),
})

export default compose(
    connect(mapStateToProps, null)
)(HeaderDesktop)
