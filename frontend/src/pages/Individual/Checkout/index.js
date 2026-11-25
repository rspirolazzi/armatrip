import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {getPackageProductsSelectedSelector, getPackageSelector} from "../../../store/package/selectors"
import {bindActionCreators, compose} from "redux"
import {postOrder} from "../../../store/order/operators"
import {
    getOrderDateSelector,
    getOrderPublicCodeUrlStateSelector,
    orderHasBeenCheckoutStateSelector
} from "../../../store/order/selectors"
import {_setOrderHasCheckout} from "../../../store/order"
import {Col, Container, Image, Row} from "react-bootstrap"
import ShareButtonModal from "../../../components/ShareButtonModal"
import withMenuRouter from "../../../layouts/withMenuRouter";
import suitcases from '../../../layouts/IndividualTourism/img/suitcases.png'
import IndividualButton from "../../../components/Individual/Button";

const IndividualCheckout = ({
                                _postOrder,
                                _setOrderHasCheckout,
                                route,
                                packageDetail,
                                products,
                                history,
                                orderHasBeenCheckout,
                                public_code_url
                            }) => {
    const [checkout, sendCheckout] = useState(false);
    useEffect(() => {
        if (!checkout && products.length > 0 && !orderHasBeenCheckout) {
            handleSubmit();
            sendCheckout(true);
        }
    });
    const handleSubmit = () => {
        if (!orderHasBeenCheckout) {
            const data = {code: packageDetail.code, products: products.map(product => product.code)}

            _postOrder(data).then(resp => {
                //_setOrderHasCheckout(true)
            })
        }
    }
    let video_link = null
    if (packageDetail.media.video_link) {
        video_link = packageDetail.media.video_link
    }

    return <Container>
        <Row className="justify-content-center">
            <Col xs={12} md={6}>
                <div className={"text-center mx-4"}>
                    <Image src={suitcases} height={160}/>
                    <h5 className={"text-white text-uppercase black mt-sm-4 mt-2 mb-0"}>{packageDetail.name}</h5>
                    <h5 className={"text-white text-uppercase black m-0 mb-4"}>te esta esperando!</h5>
                    <p className={"text-white text-uppercase book m-0 mt-4"}>En breve un asesor</p>
                    <p className={"text-white text-uppercase book m-0 mb-sm-4"}>se comunicara con vos!</p>
                    {public_code_url && <div className={'my-1'}>
                        <ShareButtonModal
                            className={'my-1 btn-lg rounded-circle btn btn-success'}
                            url={()=>route('packageShare', [public_code_url], true)}
                            message={`${packageDetail.name} te esta esperando!`}
                            target={'_blank'}>
                            <i className={'fa fa-share-alt text-white'}></i>
                        </ShareButtonModal>
                        <p className={"my-1 text-white text-uppercase black mb-2"}>Compartí tu
                            viaje
                            con tus
                            amigos</p>
                    </div>}
                    {video_link && <div className={'my-1'}>
                        <a href={video_link} className={'btn btn-info text-uppercase rounded-pill'} target={'_blank'}>Mirá
                            el destino</a>
                    </div>}
                    <div className={'my-4'}>
                        <IndividualButton variant={'primary'} invert onClick={() => route('selectPackagesGroup')}>Volver a cotizar</IndividualButton>
                    </div>
                </div>
            </Col>
        </Row>
    </Container>
};

IndividualCheckout.propTypes = {
    packageDetail: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired
};

IndividualCheckout.defaultProps = {
    packageDetail: {}
};
const mapStateToProps = state => ({
    packageDetail: getPackageSelector(state),
    products: getPackageProductsSelectedSelector(state),
    storeDate: getOrderDateSelector(state),
    public_code_url: getOrderPublicCodeUrlStateSelector(state),
    orderHasBeenCheckout: orderHasBeenCheckoutStateSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _postOrder: postOrder,
    _setOrderHasCheckout: _setOrderHasCheckout,
}, dispatch);


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps))
(IndividualCheckout)