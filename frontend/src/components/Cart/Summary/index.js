import React from "react"
import PropTypes from 'prop-types'
import {Col, Row} from "react-bootstrap"
import {Link} from "react-router-dom"
import Price from "../../Price/index"
import PurpleLink from "../../PurpleLink"
import {transformGroupByCategory} from "../../../store/package/utils"
import {route} from "../../../common/utils"
import SkyLink from "../../SkyLink"

const Summary = ({
                     code,
                     name,
                     description,
                     image,
                     icon,
                     price,
                     products,
                     price_by_passagers_tender,
                     price_by_passagers,
                     installment,
                     store,
                     className,
                     getDetailPackage = null
                 }) => <div className={className}>
    <div className={'d-md-none'}>
    <h3 className="text-center text-muted black text-uppercase mx-4 my-0">{name}</h3>
    </div>
    <div className="hide-on-fab-show mt15">
        <Row className="justify-content-sm-center">
            <Col xs={12} sm={8}>
                {products.length > 0 && <Row>
                    {transformGroupByCategory(products).map(category => <Col xs={12} md={6}
                                                                             className="font-size-16 text-uppercase border-left pl-2 pb-2"
                                                                             key={category.id}>
                        <b className={'badge badge-info p-1'}>{category.name}</b>
                        <ul className={'list-unstyled'}>
                            {category.products.map(product => <li className="font-size-14 text-uppercase book gray"
                                                                  key={product.id}><small>-{product.name}</small></li>)}
                        </ul>
                        <hr className={'mt-2 mb-1'}/>
                    </Col>)}
                </Row>}
            </Col>
        </Row>
        <p className={"mb-0 book line1 text-center"}>{installment} {installment > 1 ? 'cuotas' : 'cuota'} de </p>
        <h3 className={"mb-0 mt-3 book line1 text-center"}>
            <span className={'font-size-36 bg-brand rounded-pill text-white px-4 py-2'}>
            <Price className={"bold"} currency={store.currency.symbol} value={price_by_passagers_tender}/>
            </span>
        </h3>
        <p className={"book line1 font-size-14 text-brand mb-0  text-center mt-2"}>Precio individual del pack: <Price
            className={"bold font-size-16"}
            currency={store.currency.symbol}
            value={price_by_passagers}/></p>
        {store && store.options && store.options.currency_message &&
        <p className={"book font-size-20 text-muted my-2"}>{store.options.currency_message}</p>}

        <Row className={"mt30 line1"}>
            <Col xs={{span: 4, offset: 2}} className={"text-right child-plus"}>
                <SkyLink to={route('packageServices', [code])}></SkyLink>
                <div style={{height: '30px'}} className="mt-1 gray book font-size-12 ">AGREGAR<br></br>SERVICIOS</div>
            </Col>
            <Col xs={{span: 4}} className={"text-left"}>
                <PurpleLink to={route('prePay')}>OK</PurpleLink>
                <div style={{height: '30px'}} className="mt-1 gray book font-size-12">CONFIRMAR</div>
            </Col>
        </Row>
        {/*<Row className={"mt30 line1"}>
            <Col xs={{span: 8, offset: 2}} className={"text-center"}>
                <Coupon
                    handleCallbackAfterAddCoupon={getDetailPackage}
                />
            </Col>
        </Row>*/}
        <Row className={"mt30 line1"}>
            <Col xs={12} className={"text-center"}>
                <p className={"text-center"}><Link to={route('packageServices', [code])} className="">Volver</Link></p>
            </Col>
        </Row>
    </div>
</div>

Summary.propTypes = {
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    image: PropTypes.string,
    name: PropTypes.string.isRequired,
    price: PropTypes.number.isRequired,
    products: PropTypes.array.isRequired,
}

Summary.defaultProps = {
    products: [],
}

export default Summary