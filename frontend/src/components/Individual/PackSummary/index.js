import React from "react"
import PropTypes from 'prop-types'
import {Col, Row} from "react-bootstrap"
import {transformGroupByCategory} from "../../../store/package/utils"

const IndividualPackSummary = ({
                                   products,
                                   className,
                                   alignText='text-left'
                               }) => <div className={className}>
    <Row className="justify-content-sm-center">
        <Col xs={12}>
            {products.length > 0 && <Row className={alignText}>
                {transformGroupByCategory(products).map(category => <Col xs={12}
                                                                         className="text-uppercase pl-2 pb-2"
                                                                         key={category.id}>
                    <b className={'badge badge-primary p-1'}>{category.name}</b>
                    <ul className={'list-unstyled border-bottom rounded-bottom-dashed border-primary'}>
                        {category.products.map(product => <li className="text-uppercase" key={product.id}>
                            <small>-{product.name}</small></li>)}
                    </ul>
                </Col>)}
            </Row>}
        </Col>
    </Row>
</div>

IndividualPackSummary.propTypes = {
    code: PropTypes.string.isRequired,
    description: PropTypes.string,
    image: PropTypes.string,
    name: PropTypes.string.isRequired,
    price: PropTypes.number.isRequired,
    products: PropTypes.array.isRequired,
}

IndividualPackSummary.defaultProps = {
    products: [],
}

export default IndividualPackSummary