import React from "react"
import PropTypes from 'prop-types'
import {Link} from "react-router-dom"
import Price from "../../Price"
import {transformGroupByCategory} from "../../../store/package/utils"
import {route} from "../../../common/utils"

const ProductShare = ({
                          className,
                          user,
                          store,
                          province,
                          comments,
                          total,
                          number,
                          date,
                          school_name,
                          public_code_url,
                          passagers,
                          installment,
                          price_by_passagers,
                          price_by_passagers_tender,
                          package: packageDetail,
                          products,

                      }) => <div className={className}>
    <h3 className="text-center text-brand black mt15 font-weight-bold">Esta es la cotización que hizo {user.name}!</h3>
    <h3 className="text-center text-brand text-uppercase mb0">{packageDetail.name}</h3>
    <div className="mt15">
        {products.length > 0 && <ul className={'list-unstyled'}>
            {transformGroupByCategory(products).map(category => <li
                className="font-size-14 text-uppercase text-underline book gray" key={category.code}>{category.name}
                <ul className={'list-unstyled'}>
                    {category.products.map(product => <li className="font-size-14 text-uppercase book gray"
                                                          key={product.id}>
                        <small>-{product.name}</small>
                    </li>)}
                </ul>
            </li>)}
        </ul>}
    </div>
    <p className={"mb-0 book line1"}>{installment} {installment > 1 ? 'cuotas' : 'cuota'} de <Price
        className={"text-brand font-size-36 bold"}
        currency={store.currency.symbol}
        value={price_by_passagers_tender}/>
    </p>
    <p className={"book line1 font-size-14 text-brand mb-0"}>Precio individual del pack: <Price
        className={"bold font-size-16"}
        currency={store.currency.symbol}
        value={price_by_passagers}/></p>
    
    {store && store.options && store.options.currency_message &&
    <p className={"book font-size-20 text-muted my-2"}>{store.options.currency_message}</p>}
    <p className={"text-center mt30"}><Link to={route('home')} className="btn btn-info rounded-pill">Realizar una cotización</Link></p>
</div>

ProductShare.propTypes = {
    price: PropTypes.number,
    products: PropTypes.array.isRequired,
    package: PropTypes.object.isRequired,
}

ProductShare.defaultProps = {
    products: [],
}

export default ProductShare