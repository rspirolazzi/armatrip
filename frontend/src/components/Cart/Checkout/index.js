import React from "react"
import PropTypes from 'prop-types'
import {Card} from "react-bootstrap"
import {Link} from "react-router-dom"
import PurpleLink from "../../PurpleLink"
import {route} from "../../../common/utils"

const Checkout = ({
                      code,
                      name,
                      description,
                      image,
                      icon,
                      price,
                      products,
                  }) => <Card>
    <Card.Img variant="top" src={image || "https://via.placeholder.com/350x90?text=" + name}/>
    <Card.Body>
        <Card.Title className="text-center">{name}</Card.Title>
        <Card.Text>
            {products.length > 0 && <ul>
                {products.map(product => <li>{product.name}</li>)}
            </ul>}

        </Card.Text>
        <PurpleLink to={route('packageDetail', [code])} className={"btn-block"} rounded="pill">OK</PurpleLink>
        <p className={"text-center"}><Link to={route('packageServices', [code])} className="">Volver</Link></p>
    </Card.Body>
</Card>

Checkout.propTypes = {
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    image: PropTypes.string,
    name: PropTypes.string.isRequired,
    price: PropTypes.number.isRequired,
    products: PropTypes.array.isRequired,
}

Checkout.defaultProps = {
    products: [],
}

export default Checkout