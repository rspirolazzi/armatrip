import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {withRouter} from "react-router"
import {getBrands} from "../../store/brand/operators"
import {Col, Row} from "react-bootstrap"
import {route} from "../../common/utils"
import {Link} from "react-router-dom"
import {getBrandsSelector} from "../../store/brand/selectors"
import BrandCarousel from "../../components/Brand/Carousel"
import {getPackageSelector} from "../../store/package/selectors";

const Checkout = ({
                      _getBrands,
                      brands,
                      packageDetail,
                  }) => {

    useEffect(() => {
        _getBrands()
    }, []);

    return <>
        <Row className="justify-content-center">
            <Col xs={12} md={6}>
                <div className={"text-center"}>
                    <h4 className={"text-brand text-uppercase black mt-2 mb-0 font-weight-300"}>&iexcl;Felicitaciones!</h4>
                    <h6 className={"text-brand text-uppercase black mb-2 mt-0 font-weight-300"}>Dise&ntilde;aste tu viaje</h6>
                    <div className={'bg-brand-dark'}>
                    <div className={'content-offer img-background'}>
                        <Row>
                            <Col xs={6}>
                                <p className={"text-white text-uppercase font-size-24 title"}>Reserv&aacute; <span className={'text-warning'}>ahorra</span> y
                                    obten&eacute;</p>
                                <Row className={'text-left'}>
                                    <Col xs={3}>
                                        <span className="fa-stack fa-lg">
                                          <i className="fa fa-circle-o fa-circle-o-fine fa-stack-2x text-white"></i>
                                          <i className="fa fa-check fa-stack-2x text-warning"></i>
                                        </span>
                                    </Col>
                                    <Col xs={9}>
                                        <span className={'text-white font-size-18 bold text-uppercase'}>Cancelaci&oacute;n<br/>sin cargo</span>
                                    </Col>
                                </Row>
                                <Row className={'text-left'}>
                                    <Col xs={3}>
                                    </Col>
                                    <Col xs={9}>
                                        <hr className={'border-warning my-0'}/>
                                    </Col>
                                </Row>
                                <Row className={'text-left'}>
                                    <Col xs={3}>
                                        <span className="fa-stack fa-lg">
                                          <i className="fa fa-circle-o fa-circle-o-fine fa-stack-2x text-white"></i>
                                          <i className="fa fa-check fa-stack-2x text-warning"></i>
                                        </span>
                                    </Col>
                                    <Col xs={9}>
                                        <span className={'text-white font-size-18 bold text-uppercase'}>Tarifa<br/>congelada</span>
                                    </Col>
                                </Row>
                                <Row className={'text-left'}>
                                    <Col xs={3}>
                                    </Col>
                                    <Col xs={9}>
                                        <hr className={'border-warning my-0'}/>
                                    </Col>
                                </Row>
                                <Row className={'text-left'}>
                                    <Col xs={3}>
                                        <span className="fa-stack fa-lg">
                                          <i className="fa fa-circle-o fa-circle-o-fine fa-stack-2x text-white"></i>
                                          <i className="fa fa-check fa-stack-2x text-warning"></i>
                                        </span>
                                    </Col>
                                    <Col xs={9}>
                                        <span className={'text-white font-size-18 bold text-uppercase'}>Cuotas<br/>fijas</span>
                                    </Col>
                                </Row>
                                <a className={"text-white text-left font-size-12 mr-4"} target={'_blank'}
                                          href={"/terminos-y-condiciones-de-reserva/"}>Ver t&eacute;rminos y
                                    condiciones</a>
                            </Col>
                        </Row>
                        <Row className="justify-content-center">
                            <Col xs={12}>
                                <div className={' mx-4 my-2'}>
                                <Link to={route('creditCard')} className={'btn btn-accent btn-block text-uppercase rounded-pill'}>Reservar</Link>
                                </div>
                            </Col>
                        </Row>
                    </div>
                    </div>

                    <div className={'my-4 mx-4'}>
                        <Row className="justify-content-center">
                            <Col xs={6}>
                                <Link to={route('packageConfirm', [packageDetail.code])} className={'btn btn-info btn-block text-uppercase rounded-pill'}><i className={'fa fa-caret-left'}></i>&nbsp;Volver</Link>
                            </Col>
                            <Col xs={6}>
                                <Link to={route('checkout')} className={'btn btn-info btn-block text-uppercase rounded-pill'}>Continuar&nbsp;<i className={'fa fa-caret-right'}></i></Link>
                            </Col>
                        </Row>
                    </div>
                </div>
            </Col>
        </Row>
        {brands && brands.length > 0 ?
            <Row className="justify-content-center bg-white" style={{maxHeight: 80}}>
                <Col xs={12}>
                    <BrandCarousel brands={brands}/>
                </Col>
            </Row> : <></>}
    </>
};

Checkout.propTypes = {

};

Checkout.defaultProps = {

};
const mapStateToProps = state => ({
    brands: getBrandsSelector(state),
    packageDetail:getPackageSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _getBrands: getBrands,
}, dispatch);


export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Checkout))