import PropTypes from 'prop-types'
import React, {useEffect, useRef} from 'react'
import List from "../../components/Stores"
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {getStores, selectStore} from "../../store/store/operations"
import {getStoresSelector} from "../../store/store/selectors"
import {Col, Image, Row} from "react-bootstrap"
import {getProvinces} from "../../store/province/operators"
import {_clearOrder} from "../../store/order"
import {_clearStore} from "../../store/store"
import {_clearPackage} from "../../store/package"
import {_clearProduct} from "../../store/product"
import {url_src} from "../../store/core/_.utils";
import {isMobile} from "../../common/utils";

const Stores = ({
                    _getLoadStores,
                    stores,
                    _setSelectStore,
                    _getProvinces,
                    _clearOrder,
                    _clearStore,
                    _clearPackage,
                    _clearProduct,
                }) => {

    const storesContainer = useRef(null)

    useEffect(() => {
        const componentWillMount = () => {
            _clearOrder()
            _clearStore()
            _clearPackage()
            _clearProduct()

            _getLoadStores()
        }
        componentWillMount()
    }, [])

    const handleSelectStore = (store) => {
        _setSelectStore(store)
        _getProvinces(store.code)
    }
    const image = isMobile() ? 'movil.png' : 'desktop.png'
    return <div className={"text-center mx-4 my-4"}>
        <div ref={storesContainer} className={'stores'}>
            <h4 className={"black text-info text-uppercase font-weight-bold mb30"}>Que tipo de viaje <br></br> estás
                buscando?</h4>
            <Row className="justify-content-md-center">
                <Col xs={12} md={8}>
                    <List stores={stores} onClickSelectStore={handleSelectStore}/>
                </Col>
            </Row>
        </div>
    </div>
}

Stores.propTypes = {
    stores: PropTypes.array.isRequired
}

Stores.defaultProps = {
    stores: []
}
const mapStateToProps = state => ({
    stores: getStoresSelector(state)
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _getLoadStores: getStores,
    _setSelectStore: selectStore,
    _getProvinces: getProvinces,
    _clearOrder: _clearOrder,
    _clearStore: _clearStore,
    _clearPackage: _clearPackage,
    _clearProduct: _clearProduct,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Stores)
