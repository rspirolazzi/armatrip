import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {getPackageProductsSelectedSelector, getPackageSelector} from "../../store/package/selectors"
import {bindActionCreators} from "redux"
import {postOrder} from "../../store/order/operators"
import {withRouter} from "react-router"
import {
    getOrderDateSelector,
    getOrderPublicCodeUrlStateSelector,
    orderHasBeenCheckoutStateSelector
} from "../../store/order/selectors"
import {_clearOrder, _setOrderHasCheckout} from "../../store/order"
import {_clearStore} from "../../store/store"
import {_clearPackage} from "../../store/package"
import {_clearProduct} from "../../store/product"
import {getBrands} from "../../store/brand/operators"
import {Col, Row} from "react-bootstrap"
import {route} from "../../common/utils"
import ShareButtonModal from "../../components/ShareButtonModal"
import {Link} from "react-router-dom"
import {getBrandsSelector} from "../../store/brand/selectors"
import BrandCarousel from "../../components/Brand/Carousel"
import {url_src} from "../../store/core/_.utils";

const Checkout = ({
                      _postOrder,
                      _clearOrder,
                      _clearStore,
                      _clearPackage,
                      _setOrderHasCheckout,
                      packageDetail,
                      products,
                      history,
                      orderHasBeenCheckout,
                      _getBrands,
                      brands,
                      public_code_url
                  }) => {

    useEffect(() => {
        _getBrands()
    }, []);
    const [checkout, sendCheckout] = useState(false);
    useEffect(() => {
        if (!checkout && products.length > 0 && !orderHasBeenCheckout) {
            sendCheckout(true);
        }
    });

    const background = {
        backgroundImage: 'url(' + url_src('/img/offer-reserve.jpg') + ')',
    }
    return <>
        <Row className="justify-content-center">
            <Col xs={12} md={6}>
                <div className={"text-center"}>
                    <h4 className={"text-brand text-uppercase black mt-2 mb-0 font-weight-300"}>&iexcl;Se
                        confirm&oacute;</h4>
                    <h4 className={"text-brand text-uppercase black mt-0 mb-0 font-weight-300"}>Tu reserva!</h4>

                    <div className={'p-4 bg-secondary'}>
                        <p className={"text-white text-uppercase black mb-0 mt-0 font-weight-300"}>Compart&iacute; estos
                            beneficios</p>
                        <p className={"text-white text-uppercase black mb-0 mt-0 font-weight-300"}>con tus amig@s</p>
                        <p className={"text-white text-uppercase black mb-2 mt-0 black font-size-12"}>(V&aacute;lido por
                            tiempo limitado)</p>
                        <div className={'bg-brand-dark'}>
                            <div className={'content-offer img-background'}>
                                <Row>
                                    <Col xs={6}>
                                        <p style={{margin: '0 0 0 34px'}} className={"text-white text-uppercase font-size-20 title"}>Reserv&aacute; tu viaje <span className={'text-warning'}>ahorra</span> y obten&eacute;</p>
                                        <Row className={'text-left'}>
                                            <Col xs={3}>
                                        <span className="fa-stack fa-lg">
                                          <i className="fa fa-circle-o fa-circle-o-fine fa-stack-2x text-white"></i>
                                          <i className="fa fa-check fa-stack-2x text-warning"></i>
                                        </span>
                                            </Col>
                                            <Col xs={9}>
                                                <span
                                                    className={'text-white font-size-12 bold text-uppercase'}>Cancelaci&oacute;n<br/>sin cargo</span>
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
                                            <Col xs={8}>
                                                <span
                                                    className={'text-white font-size-12 bold text-uppercase'}>Tarifa<br/>congelada</span>
                                            </Col>
                                        </Row>
                                        <Row className={'text-left'}>
                                            <Col xs={3}>
                                            </Col>
                                            <Col xs={8}>
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
                                                <span
                                                    className={'text-white font-size-12 bold text-uppercase'}>Cuotas<br/>fijas</span>
                                            </Col>
                                        </Row>
                                        <a className={"text-white text-left font-size-12 mr-4"} target={'_blank'}
                                           href={"/terminos-y-condiciones-de-reserva/"}>Ver
                                            t&eacute;rminos y
                                            condiciones</a>
                                    </Col>
                                </Row>
                                <Row className="justify-content-center">
                                    <Col xs={12}>
                                        <div className={' mx-4 my-2'}>
                                            {/*<Link to={route('creditCard')} className={'btn btn-success btn-block text-uppercase rounded-pill'}><span className={'font-size-24'}>Compartir</span> <i className={'fa fa-whatsapp'}></i></Link>*/}

                                            {public_code_url && <div className={'my-4'}>
                                                <ShareButtonModal
                                                    className={'btn btn-success btn-block text-uppercase rounded-pill'}
                                                    url={route('packageShare', [public_code_url], true)}
                                                    message={`${packageDetail.name} te esta esperando!`}
                                                    target={'_blank'}>
                                                    <span className={'font-size-24 text-white'}>Compartir</span> <i
                                                    className={'fa fa-share-alt  text-white'}></i>
                                                </ShareButtonModal>
                                            </div>}

                                        </div>
                                    </Col>
                                </Row>
                            </div>
                        </div>
                    </div>
                    <div className={'my-4 mx-4'}>
                        <Row className="justify-content-center">
                            <Col xs={12}>
                                <Link to={route('stores')}
                                      className={'btn btn-info text-uppercase rounded-pill'}>Finalizar</Link>
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
    packageDetail: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired
};

Checkout.defaultProps = {
    packageDetail: {}
};
const mapStateToProps = state => ({
    packageDetail: getPackageSelector(state),
    products: getPackageProductsSelectedSelector(state),
    brands: getBrandsSelector(state),
    storeDate: getOrderDateSelector(state),
    public_code_url: getOrderPublicCodeUrlStateSelector(state),
    orderHasBeenCheckout: orderHasBeenCheckoutStateSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _postOrder: postOrder,
    _clearOrder: _clearOrder,
    _setOrderHasCheckout: _setOrderHasCheckout,
    _clearProduct: _clearProduct,
    _clearStore: _clearStore,
    _clearPackage: _clearPackage,
    _getBrands: getBrands,
}, dispatch);


export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Checkout))