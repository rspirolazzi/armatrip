import PropTypes from 'prop-types'
import React from "react"
import Item from '../Item/index'
import {Row} from "react-bootstrap"

const List = ({packages}) => <Row className={"mt-4"}>
    {packages.map((item, i) => <Item key={i} {...item}/>)}
</Row>

List.propTypes = {
    packages: PropTypes.array.isRequired
}

List.defaultProps = {
    packages: []
}
export default List