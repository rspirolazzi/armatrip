import React from 'react'
import {transformGroupByCategory} from "../../store/package/utils";
import {Col, Row} from "react-bootstrap";

export default function ListOfProducts({
                                           products,
                                           layout_out = {xs: 12, sm: 8},
                                           layout_in = {xs: 12, md: 6}
                                       }) {
    return <div className="">
        <Row className="justify-content-sm-center">
            <Col {...layout_out}>
                {products.length > 0 && <Row className={'list-unstyled'}>
                    {transformGroupByCategory(products).map(category => <Col {...layout_in}
                                                                             className="font-size-16 text-uppercase border-left pl-2 pb-2"
                                                                             key={category.id}>
                        <b className={'badge badge-info p-1'}>{category.name}</b>
                        <ul className={'list-unstyled'}>
                            {category.products.map(product => <li className="font-size-16 text-uppercase"
                                                                  key={product.id}>
                                <small>-{product.name}</small>
                            </li>)}
                        </ul>
                        <hr className={'mt-2 mb-1'}/>
                    </Col>)}
                </Row>}
            </Col>
        </Row>
    </div>
}