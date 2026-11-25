import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getBrands} from "../../../store/brand/operators"
import {getBrandsSelector} from "../../../store/brand/selectors"
import {getPackageSelector} from "../../../store/package/selectors";
import IndividualButton from "../../../components/Individual/Button";
import withMenuRouter from "../../../layouts/withMenuRouter";
import {Col, Row} from "react-bootstrap";

const PrePay = ({
                    _getBrands,
                    brands,
                    packageDetail,
                    route
                }) => {

    useEffect(() => {
        _getBrands()
    }, []);

    return <>
        <Row className={'justify-content-center'}>
            <Col xs={12} sm={4}>
                <div className="text-center mx-4 px-4">
                    <h4 className={"text-uppercase black mt-2 mb-0"}>&iexcl;Felicitaciones!</h4>
                    <h6 className={"text-uppercase book mb-2 mt-0"}>Dise&ntilde;aste con &eacute;xito tu
                        viaje</h6>
                    <div className={'my-3'}>
                        <p className={"text-uppercase black font-size-16 my-0"}>RESERVANDOLO POR MEDIO</p>
                        <p className={"text-uppercase black font-size-16 my-0"}>DE UN ADELANTO</p>
                        <p className={"text-uppercase black font-size-16 my-0"}>OBTENES BENEFICIOS</p>
                    </div>
                    <ul className={'list-unstyled text-left mb-3'}>
                        <li className={"text-uppercase book"}><i
                            className={'fa fa-check-circle-o text-success'}></i> Cancelaci&oacute;n sin cargo
                        </li>
                        <li className={"text-uppercase book"}><i
                            className={'fa fa-check-circle-o text-success'}></i> Tarifa
                            congelada
                        </li>
                        <li className={"text-uppercase book"}><i
                            className={'fa fa-check-circle-o text-success'}></i> Cuotas fijas
                        </li>
                    </ul>
                    <IndividualButton block variant={'info'} invert onClick={() => route('payment')}
                                      className={'btn btn-accent btn-block text-uppercase rounded-pill'}>Reservar</IndividualButton>
                    <div className={'my-3'}>
                        <p className={'book m-0'}>o hacé click en</p>
                        <p className={'book m-0'}>“continuar” y un asesor</p>
                        <p className={'book m-0'}>se pondrá en contacto con vos</p>
                    </div>
                    <IndividualButton block onClick={() => route('checkout')}>Continuar</IndividualButton>
                    <a className={"text-left font-size-12 mr-4"} target={'_blank'}
                       href={"/terminos-y-condiciones-de-reserva/"}>Ver t&eacute;rminos y condiciones</a>
                    <IndividualButton block variant={'link'}
                                      onClick={() => route('packageSummary', [packageDetail.code])}
                                      className={'btn btn-info btn-block text-uppercase rounded-pill'}>Volver</IndividualButton>
                </div>
            </Col>
        </Row>
    </>
};

PrePay.propTypes = {};

PrePay.defaultProps = {};
const mapStateToProps = state => ({
    brands: getBrandsSelector(state),
    packageDetail: getPackageSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _getBrands: getBrands,
}, dispatch);


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps)
)
(PrePay)