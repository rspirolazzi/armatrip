import React from 'react'
import {transformGroupByCategory} from "../../../store/package/utils";
import {Col, Container, Row} from "react-bootstrap";

export default function IndividualListOfProducts({
                                           products,
    alignText='text-left'
                                       }) {
    return <Container className="">
        <Row className={alignText}>
            <Col xs={12}>
                {products.length > 0 && <Row className={'list-unstyled'}>
                    {transformGroupByCategory(products).map(category => <Col xs={12}
                                                                             className="text-uppercase border-left pl-2 pb-2"
                                                                             key={category.id}>
                        <b className={'badge badge-primary p-1'}>{category.name}</b>
                        <ul className={'list-unstyled border-bottom rounded-bottom-dashed border-primary'}>
                            {category.products.map(product => <li className="text-uppercase" key={product.id}>
                                <small>-{product.name}</small>
                            </li>)}
                        </ul>
                    </Col>)}
                </Row>}
            </Col>
        </Row>
    </Container>
}