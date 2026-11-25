import PropTypes from 'prop-types'
import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {getPackages} from "../../store/package/operations"
import {getPackageGroupsSelector} from "../../store/package/selectors"
import {getSelectedStore} from "../../store/store/selectors"
import List from "../../components/PackageGroups/List"
import {Col, Row} from "react-bootstrap"
import {Link} from "react-router-dom"
import {route} from "../../common/utils"

const SelectPackages = ({
                            packages_grouped,
                            store,
                            _loadPackages,
                        }) => {

    useEffect(() => {
        if (store) {
            _loadPackages(store.code)
        }
    }, [])

    return <div className={"text-center mx-4 my-4"}>
        <h4 className={"black text-info text-uppercase font-weight-bold mb30"}>A dónde van? <br/> Buscá el mejor
            pack <br/>para cada destino</h4>
        <Row className="justify-content-center">
            <Col xs={10} md={8}>
                <List packages_grouped={packages_grouped}/>
            </Col>
        </Row>
        <Row className="justify-content-center">
            <Col xs={10} md={8}>
                <Link to={route('stores')} className={'btn btn-info text-uppercase rounded-pill'}>Volver</Link>
            </Col>
        </Row>
    </div>
}

SelectPackages.propTypes = {
    packages_grouped: PropTypes.array.isRequired
}

SelectPackages.defaultProps = {
    packages_grouped: []
}
const mapStateToProps = state => ({
    packages_grouped: getPackageGroupsSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _loadPackages: getPackages,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(SelectPackages)
