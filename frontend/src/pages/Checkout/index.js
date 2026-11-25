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
import {getBrands} from "../../store/brand/operators"
import {Col, Row} from "react-bootstrap"
import {isMobile, route} from "../../common/utils"
import ShareButtonModal from "../../components/ShareButtonModal"
import {Link} from "react-router-dom"
import {getBrandsSelector} from "../../store/brand/selectors"
import BrandCarousel from "../../components/Brand/Carousel"

const Checkout = ({
                      _postOrder,
                      _setOrderHasCheckout,
                      packageDetail,
                      products,
                      history,
                      orderHasBeenCheckout,
                      _getBrands,
                      brands,
                      public_code_url
                  }) => {
    const [checkout, sendCheckout] = useState(false);
    useEffect(() => {
        if (!checkout && products.length > 0 && !orderHasBeenCheckout) {
            handleSubmit();
            sendCheckout(true);
        }
    });
    useEffect(() => {
        _getBrands()
    }, []);
    useEffect(() => {
        return () => {
            resetBackgrounImage()
            console.log('will unmount');
        }
    }, []);
    const handleSubmit = () => {
        if (!orderHasBeenCheckout) {
            const data = {code: packageDetail.code, products: products.map(product => product.code)}

            _postOrder(data).then(resp => {
                //_setOrderHasCheckout(true)
            })
        }
    }
    let textWhite = 'text-info'
    const applyBackgrounImage = () => {
        let image = packageDetail.media.background_image;
        if (isMobile() && packageDetail.media.background_image_mobile) {
            image = packageDetail.media.background_image_mobile;
        }
        if (image) {
            textWhite = 'text-white'
            document.body.style.backgroundImage = `url(${image})`
            document.body.style.backgroundPosition = 'center center'
            document.body.style.backgroundRepeat = 'no-repeat'
            document.body.style.backgroundAttachment = 'fixed'
            document.body.style.backgroundSize = 'cover'
            document.body.style.backgroundColor = '#464646'
        }
    }
    const resetBackgrounImage = () => {
        document.body.style.backgroundImage = null;
        document.body.style.backgroundPosition = null;
        document.body.style.backgroundRepeat = null;
        document.body.style.backgroundAttachment = null;
        document.body.style.backgroundSize = null;
        document.body.style.backgroundColor = null;
    }

    let video_link = null
    if (packageDetail.media.video_link) {
        video_link = packageDetail.media.video_link
    }

    applyBackgrounImage()
    return <>
        <Row className="justify-content-center">
            <Col xs={12} md={6}>
                <div className={"text-center mx-4"}>
                    <h4 className={"" + textWhite + " text-uppercase black my-4 font-weight-300"}>{packageDetail.name} te
                        esta
                        esperando!</h4>
                    <h6 className={"" + textWhite + " text-uppercase black my-4 font-weight-300"}>En breve un asesor
                        <br/>se comunicara
                        <br/>con vos.</h6>
                    {public_code_url && <div className={'my-4'}>
                        <ShareButtonModal
                            className={'my-4 btn-lg rounded-circle btn btn-info'}
                            url={route('packageShare', [public_code_url], true)}
                            message={`${packageDetail.name} te esta esperando!`}
                            target={'_blank'}>
                            <i className={'fa fa-share-alt'}></i>
                        </ShareButtonModal>
                        <p className={"my-4 " + textWhite + " text-uppercase black mb-2 font-weight-300"}>Compartí tu
                            viaje
                            con tus
                            amigos</p>
                    </div>}
                    {video_link && <div className={'my-4'}>
                        <a href={video_link} className={'btn btn-info text-uppercase rounded-pill'} target={'_blank'}>Mirá
                            el destino</a>
                    </div>}
                    <div className={'my-4'}>
                        <Link to={route('stores')} className={'btn btn-info text-uppercase rounded-pill'}>Volver a
                            cotizar</Link>
                    </div>
                </div>
            </Col>
        </Row>
        {brands && brands.length > 0 ?
            <Row className="justify-content-center bg-white" style={{maxHeight:80}}>
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
    public_code_url:getOrderPublicCodeUrlStateSelector(state),
    orderHasBeenCheckout: orderHasBeenCheckoutStateSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _postOrder: postOrder,
    _clearOrder: _clearOrder,
    _setOrderHasCheckout: _setOrderHasCheckout,
    _getBrands: getBrands,
}, dispatch);


export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Checkout))