import React from "react"
import {Col} from "react-bootstrap"
import {url_src} from "../../../store/core/_.utils";

const ItemEmpty = ({}) => <Col xs={6} className={"text-center mb15 d-block d-sm-none"}>
    <img alt={'Armatrip'} width={'100%'} src={url_src('/img/stores/cancel.png')}/>
</Col>
export default ItemEmpty

ItemEmpty.propTypes = {}

ItemEmpty.defaultProps = {}