import PropTypes from 'prop-types'
import React from "react"
import {Col} from "react-bootstrap"
import {Link} from "react-router-dom"
import {route} from "../../../common/utils"

const Item = ({code, name, icon, onClickSelectStore}) => <Col xs={6} md={4} className={"text-center mb15"}>
    <Link onClick={onClickSelectStore} to={route('selectPackages', [code])}>
        {icon && <img alt={''} className={'w-100'} src={icon}/>}
    <div className={'text-muted text-uppercase font-weight-bold'}>{name}</div>
</Link>
</Col>
export default Item

Item.propTypes = {
    icon: PropTypes.string,
    name: PropTypes.string.isRequired,
    code: PropTypes.string.isRequired,
    onClickSelectStore: PropTypes.func.isRequired,
}

Item.defaultProps = {
    icon: '',
}