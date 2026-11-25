import PropTypes from 'prop-types'
import React from "react"
import Item from '../Item'
import ItemEmpty from '../ItemEmpty'
import {Row} from "react-bootstrap"

const List = ({stores, onClickSelectStore}) => {
    let is_even = (stores.length % 2) === 0
    return <Row className={"mt-3"}>
        {stores.map((item, i) => <Item key={i} {...item} onClickSelectStore={() => onClickSelectStore(item)}/>)}
        {!is_even && <ItemEmpty />}
    </Row>
}

List.propTypes = {
    stores: PropTypes.array.isRequired,
    onClickSelectStore: PropTypes.func.isRequired,
}

List.defaultProps = {
    stores: [],
}
export default List