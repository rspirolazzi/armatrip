import PropTypes from 'prop-types'
import React from "react"
import {Col} from "react-bootstrap"
import {Link} from "react-router-dom"
import {url_src} from "../../../../store/core/_.utils";
import {route} from "../../../../common/utils"

const Item = ({code, name,}) => <Col xs={6} className={"text-center"}><Link to={route('packageDetail', [code])}>
    <img width={'100%'} src={url_src('/img/icon.png')} />
    <div className={"text-center font-weight-bold"}>{name}</div>
</Link>
</Col>
export default Item

Item.propTypes = {
    image_primary: PropTypes.string,
    name: PropTypes.string.isRequired,
    code: PropTypes.string.isRequired,
}

Item.defaultProps = {
    image_primary: '',
}