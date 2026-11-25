import React from "react"
import PropTypes from 'prop-types'
import {Col, Row} from "react-bootstrap"
import {Link} from "react-router-dom"
import Price from "../../Price"
import PurpleLink from "../../PurpleLink"
import SkyLink from "../../SkyLink"
import {route} from "../../../common/utils"
import ListOfProducts from "../../ListOfProducts";

const PackageServiceDetail = ({
                           code,
                           name,
                           description,
                           image,
                           icon,
                           price,
                           products,
                           passagers,
                           installment,
                           tender_installment,
                           price_by_passagers,
                           price_by_passagers_tender,
                           store,
                           user,
                           className,
                           getDetailPackage = null,
                       }) => <div className={className}>
    <div className={'d-md-none'}>
    <h3 className="text-center text-muted font-size-24 mx-4 my-0">Hola {user.name}!</h3>
    <h3 className="text-center text-muted black text-uppercase mx-4 my-0">{name}</h3>
    </div>
    <ListOfProducts products={products}/>
        <p className={"mb-0 book line1 text-center"}>{installment} {installment > 1 ? 'cuotas' : 'cuota'} de </p>
        <h3 className={"mb-0 mt-3 book line1 text-center"}>
            <span className={'font-size-36 bg-brand rounded-pill text-white px-4 py-2'}>
            <Price className={"bold"} currency={store.currency.symbol} value={price_by_passagers_tender}/>
            </span>
        </h3>
    <p className={"book line1 font-size-14 text-brand mb-0  text-center"}>Precio individual del pack: <Price
        className={"bold font-size-16"}
        currency={store.currency.symbol}
        value={price_by_passagers}/></p>
    {store && store.options && store.options.currency_message &&
    <p className={"book font-size-20 text-muted my-2"}>{store.options.currency_message}</p>}
    <Row className={"mt30 line1"}>
        <Col xs={{ span: 4, offset: 2 }} className={"text-right child-plus"}>
            <SkyLink to={route('packageServices', [code])}></SkyLink>
            <div style={{height: '30px'}} className="mt-1 gray book font-size-12 ">AGREGAR<br></br>SERVICIOS</div>
        </Col>
        <Col xs={{ span: 4}} className={"text-left"}>
            <PurpleLink to={route('prePay')}>OK</PurpleLink>
            <div style={{height: '30px'}} className="mt-1 gray book font-size-12">CONFIRMAR</div>
        </Col>
    </Row>
    <Row className={"mt30 line1"}>
        {/*<Col xs={{ span: 8, offset: 2 }} className={"text-center"}>
            <Coupon
                handleCallbackAfterAddCoupon={getDetailPackage}
            />
        </Col>*/}
    </Row>
    <p className={"text-center mt30"}><Link to={route('register')} className="">Volver</Link></p>
</div>

PackageServiceDetail.propTypes = {
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    image: PropTypes.string,
    name: PropTypes.string.isRequired,
    price: PropTypes.number,
    products: PropTypes.array.isRequired,
}

PackageServiceDetail.defaultProps = {
    products: [],
}

export default PackageServiceDetail