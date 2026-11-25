import PropTypes from 'prop-types'
import React, {useEffect, useState} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import ProductShare from '../../../components/Cart/ProductShare'
import {Col, Row} from "react-bootstrap"
import {getOrderShare} from "../../../store/order/operators"
import {shareOrderStateSelector} from "../../../store/order/selectors"

const Share = ({
                   order,
                   _loadOrderDetail,
                   match
               }) => {
    const [loaded, setLoaded] = useState(false)

    useEffect(() => {
        if (!loaded) {
            _loadOrderDetail(match.params.code)
            setLoaded(true)
        }
    })

    return <Row className="justify-content-center">
        <Col xs={12} md={6}>
            {order && <ProductShare className={'mx-4'} {...order}/>}
        </Col>
    </Row>
}

Share.propTypes = {
    order: PropTypes.object,
}

Share.defaultProps = {
    order: null
}
const mapStateToProps = state => ({
    order: shareOrderStateSelector(state),
})

const mapDispatchToProps = dispatch => bindActionCreators({
    _loadOrderDetail: getOrderShare,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Share)
